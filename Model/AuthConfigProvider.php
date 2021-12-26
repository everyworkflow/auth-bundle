<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Model;

use EveryWorkflow\CoreBundle\Model\BaseConfigProvider;

class AuthConfigProvider extends BaseConfigProvider implements AuthConfigProviderInterface
{
    public function getPermissions(?string $path = null): mixed
    {
        if ($path) {
            $path = '.' . $path;
        }
        return $this->get('permissions' . $path);
    }
}
