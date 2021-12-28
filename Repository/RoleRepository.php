<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Repository;

use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use EveryWorkflow\MongoBundle\Support\Attribute\RepositoryAttribute;
use EveryWorkflow\AuthBundle\Document\RoleDocument;
use EveryWorkflow\AuthBundle\Document\RoleDocumentInterface;
use EveryWorkflow\AuthBundle\Model\AuthConfigProviderInterface;
use Symfony\Contracts\Service\Attribute\Required;

#[RepositoryAttribute(documentClass: RoleDocument::class, primaryKey: 'code')]
class RoleRepository extends BaseDocumentRepository implements RoleRepositoryInterface
{
    protected AuthConfigProviderInterface $authConfigProvider;

    #[Required]
    public function setAuthConfigProvider(AuthConfigProviderInterface $authConfigProvider): self
    {
        $this->authConfigProvider = $authConfigProvider;

        return $this;
    }

    public function getPermissionsForRoles(array $roles): array
    {
        $permissions = [];

        if (in_array('admin', $roles, true) || in_array('ROLE_ADMIN', $roles, true)) {
            foreach ($this->authConfigProvider->getPermissions() as $key1 => $group) {
                foreach ($group as $key2 => $permission) {
                    $permissionCode = $key1 . '.' . $key2;
                    if (!isset($permissions[$permissionCode])) {
                        $permissions[$permissionCode] = '';
                    }
                }
            }
        } else {
            try {
                /** @var RoleDocumentInterface[] @rolesData */
                $rolesData = $this->find([
                    'code' => ['$in' => $roles],
                ]);
                foreach ($rolesData as $roleData) {
                    foreach ($roleData->getPermissions() as $permission) {
                        if (!isset($permissions[$permission])) {
                            $permissions[$permission] = '';
                        }
                    }
                }
            } catch (\Exception $e) {
            }
        }

        return array_keys($permissions);
    }
}
