<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Controller\Role;

use EveryWorkflow\AuthBundle\Repository\RoleRepositoryInterface;
use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteRoleController extends AbstractController
{
    protected RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    #[EwRoute(
        path: "auth/role/{uuid}",
        name: 'auth.role.delete',
        methods: 'DELETE',
        permissions: 'auth.role.delete',
        swagger: [
            'parameters' => [
                [
                    'name' => 'uuid',
                    'in' => 'path',
                ]
            ]
        ]
    )]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $this->roleRepository->deleteOneByFilter(['_id' => new \MongoDB\BSON\ObjectId($uuid)]);
            return new JsonResponse(['detail' => 'ID: ' . $uuid . ' deleted successfully.']);
        } catch (\Exception $e) {
            return new JsonResponse(['detail' => $e->getMessage()], 500);
        }
    }
}
