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
    public function getUsername(): ?string
    {
        return $this->getData(self::KEY_USERNAME);
    }

    public function setUsername(string $username): self
    {
        $this->setData(self::KEY_USERNAME, $username);

        return $this;
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

    public function getRoles(): array
    {
        $roles = $this->getData(self::KEY_ROLES);
        if ($roles instanceof \MongoDB\Model\BSONArray) {
            $roles = $roles->getArrayCopy();
        }
        return $roles ?? [];
    }

    public function setRoles(array $roles): self
    {
        $this->setData(self::KEY_ROLES, $roles);

        return $this;
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
