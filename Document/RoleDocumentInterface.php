<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Document;

use EveryWorkflow\MongoBundle\Document\BaseDocumentInterface;
use EveryWorkflow\MongoBundle\Document\HelperTrait\CreatedUpdatedHelperTraitInterface;
use EveryWorkflow\MongoBundle\Document\HelperTrait\StatusHelperTraitInterface;

interface RoleDocumentInterface extends BaseDocumentInterface, CreatedUpdatedHelperTraitInterface, StatusHelperTraitInterface
{
    public const KEY_CODE = 'code';
    public const KEY_NAME = 'name';
    public const KEY_PERMISSIONS = 'permissions';

    public function setCode(string $code): self;

    public function getCode(): ?string;

    public function setName(string $name): self;

    public function getName(): ?string;

    public function setPermissions(string $permissions): self;

    public function getPermissions(): array;
}
