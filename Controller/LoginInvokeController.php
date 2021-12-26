<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Controller;

use EveryWorkflow\AuthBundle\Model\AuthConfigProviderInterface;
use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LoginInvokeController extends AbstractController
{
    protected AuthConfigProviderInterface $authConfigProvider;

    public function __construct(AuthConfigProviderInterface $authConfigProvider)
    {
        $this->authConfigProvider = $authConfigProvider;
    }

    #[EwRoute(path: "auth/login/invoke", name: 'auth.login.invoke', methods: 'POST')]
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse();
    }
}
