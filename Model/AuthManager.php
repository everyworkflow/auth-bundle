<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Model;

use EveryWorkflow\AuthBundle\Repository\LoginRepositoryInterface;
use EveryWorkflow\AuthBundle\Repository\LoginSessionRepositoryInterface;
use EveryWorkflow\AuthBundle\Repository\RoleRepositoryInterface;
use EveryWorkflow\AuthBundle\Security\AuthUser;
use EveryWorkflow\CoreBundle\Model\SystemDateTimeInterface;
use EveryWorkflow\MongoBundle\Document\BaseDocument;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepositoryInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthManager implements AuthManagerInterface
{
    public function __construct(
        protected UserPasswordHasherInterface $userPasswordHasher,
        protected BaseDocumentRepositoryInterface $baseDocumentRepository,
        protected LoginRepositoryInterface $loginRepository,
        protected LoginSessionRepositoryInterface $loginSessionRepository,
        protected RoleRepositoryInterface $roleRepository,
        protected JWTTokenManagerInterface $JWTManager,
        protected SystemDateTimeInterface $systemDateTime,
        protected LoggerInterface $logger,
        protected string $collectionName = 'user_entity_collection',
        protected string $usernameKey = 'email',
        protected string $authType = 'user',
        protected array $additionalKeys = ['first_name', 'last_name']
    ) {
    }

    protected function generateToken(AuthUser $authUser): string
    {
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $passwordHasherFactory = new PasswordHasherFactory([
            AuthUser::class => [
                'algorithm' => 'auto',
                'cost' => 4,
                'time_cost' => 3,
                'memory_cost' => 10,
            ],
        ]);

        return $passwordHasherFactory->getPasswordHasher($authUser)->hash($token);
    }

    protected function getDocumentRepository(?string $collectionName = null): BaseDocumentRepositoryInterface
    {
        if (null === $collectionName) {
            $collectionName = $this->collectionName;
        }
        return $this->baseDocumentRepository->setDocumentClass(BaseDocument::class)
            ->setCollectionName($collectionName);
    }

    protected function getAuthUserFromDocument(BaseDocument $document): AuthUser
    {
        $authUser = new AuthUser();
        $authUser->setId($document->getId());
        $authUser->setUsername($document->getData($this->usernameKey));
        $authUser->setRoles($document->getData('roles') ?? []);
        $authUser->setPermissions($this->roleRepository->getPermissionsForRoles($authUser->getRoles()));
        $authUser->setAuthType($this->authType);
        foreach ($this->additionalKeys as $key) {
            if ($document->getData($key)) {
                $authUser->setData($key, $document->getData($key));
            }
        }
        return $authUser;
    }

    /**
     * @throws Exception
     */
    public function session(string $username, string $password): array
    {
        try {
            $item = $this->getDocumentRepository()->findOne([$this->usernameKey => $username]);
        } catch (Exception $e) {
            throw new Exception('Invalid credentials.');
        }

        $authUser = new AuthUser($item->toArray());
        if (!$this->userPasswordHasher->isPasswordValid($authUser, $password)) {
            throw new Exception('Invalid credentials.');
        }

        $sessionToken = $this->generateToken($authUser);

        $login = $this->loginRepository->create([
            'collection_name' => $this->collectionName,
            'username_key' => $this->usernameKey,
            'username' => $username,
            'session_token' => $sessionToken,
            'status' => 'disable',
        ]);
        $this->loginRepository->saveOne($login);

        return [
            'session_token' => $sessionToken
        ];
    }

    /**
     * @throws Exception
     */
    public function JWT(string $sessionToken, string $sessionName = 'Not defined'): array
    {
        try {
            $login = $this->loginRepository->findOne([
                'session_token' => $sessionToken,
                'status' => 'disable',
                'created_at' => ['$lt' => $this->systemDateTime->nowFormat('-5 minute')],
            ]);
            $item = $this->getDocumentRepository($login->getData('collection_name'))->findOne([
                $login->getData('username_key') => $login->getData('username')
            ]);
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
            throw new Exception('Invalid session token.');
        }

        $authUser = $this->getAuthUserFromDocument($item);
        $authUser->setData('session_token', $sessionToken);
        $token = $this->JWTManager->create($authUser);
        $refreshToken = $this->generateToken($authUser);

        $loginSession = $this->loginSessionRepository->create([
            'collection_name' => $login->getData('collection_name'),
            'username_key' => $login->getData('username_key'),
            'username' => $item->getData($login->getData('username_key')),
            // 'user_agent' => $request->headers->get('user-agent'),
            'name' => $sessionName,
            'session_token' => $sessionToken,
            'refresh_token' => $refreshToken,
            'refresh_count' => 1,
            'status' => 'enable',
        ]);
        $this->loginSessionRepository->saveOne($loginSession);

        $login->setData('status', 'enable');
        $this->loginRepository->updateOne($login);

        return [
            'session_token' => $sessionToken,
            'refresh_token' => $refreshToken,
            'token' => $token,
        ];
    }

    /**
     * @throws Exception
     */
    public function refreshJWT(string $sessionToken, string $refreshToken): array
    {
        try {
            $loginSession = $this->loginSessionRepository->findOne([
                'session_token' => $sessionToken,
                'refresh_token' => $refreshToken,
                'status' => 'enable',
                'created_at' => ['$gt' => $this->systemDateTime->nowFormat('-90 days')], // Keep out 3 months old sessions
                'updated_at' => ['$gt' => $this->systemDateTime->nowFormat('-7 days')], // Keep out 7 days untouched sessions
            ]);
            $item = $this->getDocumentRepository()->findOne([
                $loginSession->getData('username_key') => $loginSession->getData('username')
            ]);
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
            throw new Exception('Invalid session token or refresh token.');
        }

        $authUser = $this->getAuthUserFromDocument($item);
        $authUser->setType($this->authType);
        $authUser->setData('session_token', $sessionToken);
        if (count($authUser->getRoles())) {
            $authUser->setPermissions($this->roleRepository->getPermissionsForRoles($authUser->getRoles()));
        }
        $newToken = $this->JWTManager->create($authUser);
        $newRefreshToken = $this->generateToken($authUser);

        $loginSession->setData('refresh_token', $newRefreshToken)
            ->setData('refresh_count', $loginSession->getData('refresh_count') + 1);
        $this->loginSessionRepository->saveOne($loginSession);

        return [
            'session_token' => $sessionToken,
            'refresh_token' => $newRefreshToken,
            'token' => $newToken,
        ];
    }
}
