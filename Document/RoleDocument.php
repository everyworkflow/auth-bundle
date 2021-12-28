<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Document;

use EveryWorkflow\CoreBundle\Validation\Type\ArrayValidation;
use EveryWorkflow\CoreBundle\Validation\Type\StringValidation;
use EveryWorkflow\MongoBundle\Document\BaseDocument;
use EveryWorkflow\MongoBundle\Document\HelperTrait\CreatedUpdatedHelperTrait;
use EveryWorkflow\MongoBundle\Document\HelperTrait\StatusHelperTrait;

class RoleDocument extends BaseDocument implements RoleDocumentInterface
{
    use CreatedUpdatedHelperTrait, StatusHelperTrait;

    #[StringValidation(required: true, minLength: 2, maxLength: 20)]
    public function setCode(string $code): self
    {
        $this->setData(self::KEY_CODE, $code);
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->getData(self::KEY_CODE);
    }

    #[StringValidation(required: true, minLength: 2, maxLength: 100)]
    public function setName(string $name): self
    {
        $this->setData(self::KEY_NAME, $name);
        return $this;
    }

    public function getName(): ?string
    {
        return $this->getData(self::KEY_NAME);
    }

    #[ArrayValidation()]
    public function setPermissions(string $permissions): self
    {
        $this->setData(self::KEY_PERMISSIONS, $permissions);
        return $this;
    }

    public function getPermissions(): array
    {
        $permissions = $this->getData(self::KEY_PERMISSIONS);
        if ($permissions instanceof \MongoDB\Model\BSONArray) {
            return $permissions->getArrayCopy();
        }
        return [];
    }
}
