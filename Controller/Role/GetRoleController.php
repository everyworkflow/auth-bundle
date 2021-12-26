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
    public function __invoke(string $uuid = 'create'): JsonResponse
    {
        $data = [
            'data_form' => $this->roleForm->toArray(),
        ];

        if ('create' !== $uuid) {
            try {
                $entity = $this->roleRepository->findById($uuid);
                $data['item'] = $entity->toArray();
            } catch (\Exception $e) {
                // ignore if _id doesn't exist
            }
        }

        return new JsonResponse($data);
    }
}
