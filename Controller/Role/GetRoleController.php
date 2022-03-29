<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Controller\Role;

use EveryWorkflow\AuthBundle\Form\RoleFormInterface;
use EveryWorkflow\AuthBundle\Repository\RoleRepositoryInterface;
use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetRoleController extends AbstractController
{
    protected RoleRepositoryInterface $roleRepository;
    protected RoleFormInterface $roleForm;

    public function __construct(
        RoleRepositoryInterface $roleRepository,
        RoleFormInterface $roleForm
    ) {
        $this->roleRepository = $roleRepository;
        $this->roleForm = $roleForm;
    }

    #[EwRoute(
        path: "auth/role/{uuid}",
        name: 'auth.role.view',
        methods: 'GET',
        permissions: 'auth.role.view',
        swagger: [
            'parameters' => [
                [
                    'name' => 'uuid',
                    'in' => 'path',
                    'default' => 'create',
                ]
            ]
        ]
    )]
    public function __invoke(Request $request, string $uuid = 'create'): JsonResponse
    {
        $data = [];

        if ('create' !== $uuid) {
            try {
                $entity = $this->roleRepository->findById($uuid);
                $data['item'] = $entity->toArray();
            } catch (\Exception $e) {
                // ignore if _id doesn't exist
            }
        }

        if ($request->get('for') === 'data-form') {
            $data['data_form'] = $this->roleForm->toArray();
        }

        return new JsonResponse($data);
    }
}
