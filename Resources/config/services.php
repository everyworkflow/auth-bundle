<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use EveryWorkflow\AuthBundle\Controller\Role\ListRoleController;
use EveryWorkflow\AuthBundle\EventListener\JWTCreatedListener;
use EveryWorkflow\AuthBundle\EventListener\ControllerPermissionListener;
use EveryWorkflow\AuthBundle\Form\RoleForm;
use EveryWorkflow\AuthBundle\GridConfig\RoleGridConfig;
use EveryWorkflow\AuthBundle\Model\AuthConfigProvider;
use EveryWorkflow\AuthBundle\Repository\RoleRepository;
use EveryWorkflow\AuthBundle\Security\Guard\AuthAuthenticator;
use EveryWorkflow\DataGridBundle\Model\Collection\RepositorySource;
use EveryWorkflow\DataGridBundle\Model\DataGrid;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('EveryWorkflow\\AuthBundle\\', '../../*')
        ->exclude('../../{DependencyInjection,Resources,Tests}');

    $services->set('ew.auth.authenticator', AuthAuthenticator::class)
        ->parent('lexik_jwt_authentication.security.jwt_authenticator');

    $services->set(JWTCreatedListener::class)
        ->tag('kernel.event_listener', [
            'event' => 'lexik_jwt_authentication.on_jwt_created',
            'method' => 'onJWTCreated',
        ]);

    $services->set(ControllerPermissionListener::class)
        ->tag('kernel.event_listener', [
            'event' => 'kernel.controller',
            'method' => 'onKernelController',
        ]);

    $services->set('ew_auth_role_grid_config', RoleGridConfig::class);
    $services->set('ew_auth_role_grid_source', RepositorySource::class)
        ->arg('$baseRepository', service(RoleRepository::class))
        ->arg('$dataGridConfig', service('ew_auth_role_grid_config'))
        ->arg('$form', service(RoleForm::class));
    $services->set('ew_auth_role_grid', DataGrid::class)
        ->arg('$source', service('ew_auth_role_grid_source'))
        ->arg('$dataGridConfig', service('ew_auth_role_grid_config'))
        ->arg('$form', service(RoleForm::class));
    $services->set(ListRoleController::class)
        ->arg('$dataGrid', service('ew_auth_role_grid'));
};
