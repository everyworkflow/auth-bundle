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
    public const KEY_USERNAME = 'username';
    public const KEY_PASSWORD = 'password';
    public const KEY_ROLES = 'roles';
}
