<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

namespace EveryWorkflow\AuthBundle\EventListener;

use EveryWorkflow\AuthBundle\Security\AuthUserInterface;
use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use ReflectionClass;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ControllerPermissionListener
{
    protected AuthUserInterface $authUser;

    public function __construct(
        AuthUserInterface $authUser
    ) {
        $this->authUser = $authUser;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        $requestedRouteName = $event->getRequest()->attributes->get('_route');
        // $classMethodAttributeData = [];
        $classMethodAttributeData = $this->getClassMethodAttributeData(get_class($controller));
        foreach ($classMethodAttributeData as $attributeData) {
            if (
                isset($attributeData['name'], $attributeData['permissions'])
                && $attributeData['name'] === $requestedRouteName
                && $attributeData['permissions'] !== null
            ) {
                if (is_array($attributeData['permissions'])) {
                    foreach ($attributeData['permissions'] as $permission) {
                        $this->checkPermissionAndThrowIfNeeded($permission);
                    }
                } else if (is_string($attributeData['permissions'])) {
                    $this->checkPermissionAndThrowIfNeeded($attributeData['permissions']);
                }

                break;
            }
        }
    }

    protected function getClassMethodAttributeData(string $className): array
    {
        $data = [];

        $reflectionClass = new ReflectionClass($className);
        foreach ($reflectionClass->getMethods() as $method) {
            foreach ($method->getAttributes() as $attribute) {
                if ($attribute->getName() === EwRoute::class) {
                    $attrArgs = $attribute->getArguments();
                    $data[$method->getName()] = $attrArgs;
                }
            }
        }

        return $data;
    }

    protected function checkPermissionAndThrowIfNeeded(string $permission): void
    {
        if (!in_array($permission, $this->authUser->getData('permissions') ?? [])) {
            throw new AccessDeniedHttpException('You do not have permission to access this resource.');
        }
    }
}
