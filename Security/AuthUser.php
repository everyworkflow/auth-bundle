<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Security;

use EveryWorkflow\CoreBundle\Model\DataObject;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthUser extends DataObject implements AuthUserInterface
{
    public function setId(string $id): self
    {
        $this->setData(self::KEY_ID, $id);

        return $this;
    }

    public function getId(): ?string
    {
        return $this->getData(self::KEY_ID);
    }

    public function setUsername(string $username): self
    {
        $this->setData(self::KEY_USERNAME, $username);

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->getData(self::KEY_USERNAME);
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername() ?? '';
    }

    public function setPassword(string $password): self
    {
        $this->setData(self::KEY_PASSWORD, $password);

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->getData(self::KEY_PASSWORD);
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->setData(self::KEY_PASSWORD, null);
    }

    public function setRoles(array $roles): self
    {
        $this->setData(self::KEY_ROLES, $roles);

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->getData(self::KEY_ROLES);
        if ($roles instanceof \MongoDB\Model\BSONArray) {
            $roles = $roles->getArrayCopy();
        }
        return $roles ?? [];
    }

    public function setPermissions(array $permissions): self
    {
        $this->setData(self::KEY_PERMISSIONS, $permissions);

        return $this;
    }

    public function getPermissions(): array
    {
        return $this->getData(self::KEY_PERMISSIONS) ?? [];
    }

    public function setAuthType(string $authType): self
    {
        $this->setData(self::KEY_AUTH_TYPE, $authType);

        return $this;
    }

    public function getAuthType(): ?string
    {
        return $this->getData(self::KEY_AUTH_TYPE);
    }

    public function isEqualTo(UserInterface $user): bool
    {
        return $this->username === $user->getUserIdentifier();
    }

    public function __call(string $name, array $arguments)
    {
        return $this->getUsername();
    }

    public static function createFromPayload($username, array $payload): self
    {
        return (new self())->setUsername($username);
    }
}
