<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Form;

use EveryWorkflow\AuthBundle\Model\AuthConfigProviderInterface;
use EveryWorkflow\CoreBundle\Model\DataObjectInterface;
use EveryWorkflow\DataFormBundle\Factory\FieldOptionFactoryInterface;
use EveryWorkflow\DataFormBundle\Factory\FormFieldFactoryInterface;
use EveryWorkflow\DataFormBundle\Field\Select\Option;
use EveryWorkflow\DataFormBundle\Model\Form;

class RoleForm extends Form implements RoleFormInterface
{
    protected AuthConfigProviderInterface $authConfigProvider;
    protected FieldOptionFactoryInterface $fieldOptionFactory;

    public function __construct(
        DataObjectInterface $dataObject,
        FormFieldFactoryInterface $formFieldFactory,
        AuthConfigProviderInterface $authConfigProvider,
        FieldOptionFactoryInterface $fieldOptionFactory
    ) {
        parent::__construct($dataObject, $formFieldFactory);
        $this->authConfigProvider = $authConfigProvider;
        $this->fieldOptionFactory = $fieldOptionFactory;
    }

    protected function getPermissionOptions(): array
    {
        $options = [];
        $sortOrder = 1;
        foreach ($this->authConfigProvider->getPermissions() as $group => $item) {
            $childOptions = [];
            foreach ($item as $key => $val) {
                $childOption = $this->fieldOptionFactory->create(Option::class, [
                    'title' => $val,
                    'value' => $group . '.' . $key,
                    'sort_order' => $sortOrder,
                ]);
                ++$sortOrder;
                $childOptions[] = $childOption;
            }
            $options[] = $this->fieldOptionFactory->create(Option::class, [
                'title' => $group,
                'value' => $group,
                'sort_order' => $sortOrder,
                'children' => $childOptions,
            ]);
        }

        return $options;
    }

    public function getFields(): array
    {
        $fields = [
            $this->formFieldFactory->createField([
                'label' => 'UUID',
                'name' => '_id',
                'is_readonly' => true,
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Name',
                'name' => 'name',
                'field_type' => 'text_field',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Code',
                'name' => 'code',
                'field_type' => 'text_field',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Permissions',
                'name' => 'permissions',
                'field_type' => 'select_field',
                'options' => $this->getPermissionOptions(),
                'treeProps' => [
                    'treeCheckable' => true,
                    'showCheckedStrategy' => true,
                ]
            ]),
        ];

        $sortOrder = 5;
        foreach ($fields as $field) {
            $field->setSortOrder($sortOrder++);
        }

        return array_merge($fields, parent::getFields());
    }
}
