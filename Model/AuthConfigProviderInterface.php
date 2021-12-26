<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Model;

use EveryWorkflow\CoreBundle\Model\BaseConfigProviderInterface;

interface AuthConfigProviderInterface extends BaseConfigProviderInterface
{
    public function getPermissions(?string $path = null): mixed;
}
