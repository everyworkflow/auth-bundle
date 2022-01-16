<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('auth');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('permissions')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('type')
                    ->arrayPrototype()
                    ->scalarPrototype()
                    ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
