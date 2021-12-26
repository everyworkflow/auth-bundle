<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Repository;

use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use EveryWorkflow\MongoBundle\Support\Attribute\RepositoryAttribute;
use EveryWorkflow\AuthBundle\Document\RoleDocument;
use EveryWorkflow\AuthBundle\Model\AuthConfigProviderInterface;
use Symfony\Contracts\Service\Attribute\Required;

#[RepositoryAttribute(documentClass: RoleDocument::class)]
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
        /* TODO: Currently all permissions are provided to all */
        foreach ($this->authConfigProvider->getPermissions() as $key1 => $group) {
            foreach ($group as $key2 => $permission) {
                $permissions[] = $key1 . '.' . $key2;
            }
        }

        return $permissions;
    }
}
