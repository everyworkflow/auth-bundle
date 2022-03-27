<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Security;

use EveryWorkflow\CoreBundle\Model\DataObjectInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface AuthUserInterface extends DataObjectInterface, JWTUserInterface, EquatableInterface, PasswordAuthenticatedUserInterface
{
    public const KEY_ID = '_id';
    public const KEY_USERNAME = 'username';
    public const KEY_PASSWORD = 'password';
    public const KEY_ROLES = 'roles';
    public const KEY_PERMISSIONS = 'permissions';
    public const KEY_AUTH_TYPE = 'auth_type';

    public function setId(string $id): self;

    public function getId(): ?string;

    public function setUsername(string $username): self;

    public function getUsername(): ?string;

    public function getUserIdentifier(): string;

    public function setPassword(string $password): self;

    public function setRoles(array $roles): self;

    public function getRoles(): array;

    public function setPermissions(array $permissions): self;

    public function getPermissions(): array;

    public function setAuthType(string $authType): self;

    public function getAuthType(): ?string;
}
