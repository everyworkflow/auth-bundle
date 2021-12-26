<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

interface AuthUserProviderInterface extends UserProviderInterface, PayloadAwareUserProviderInterface
{
    // Something
}
