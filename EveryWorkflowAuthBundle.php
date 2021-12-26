<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle;

use EveryWorkflow\AuthBundle\DependencyInjection\AuthExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EveryWorkflowAuthBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new AuthExtension();
    }
}
