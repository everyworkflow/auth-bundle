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
use EveryWorkflow\DataFormBundle\Factory\FormSectionFactoryInterface;
use EveryWorkflow\DataFormBundle\Field\Select\Option;
use EveryWorkflow\DataFormBundle\Model\Form;

class RoleForm extends Form implements RoleFormInterface
{
    protected AuthConfigProviderInterface $authConfigProvider;
    protected FieldOptionFactoryInterface $fieldOptionFactory;

    public function __construct(
        DataObjectInterface $dataObject,
        FormSectionFactoryInterface $formSectionFactory,
        FormFieldFactoryInterface $formFieldFactory,
        AuthConfigProviderInterface $authConfigProvider,
        FieldOptionFactoryInterface $fieldOptionFactory
    ) {
        parent::__construct($dataObject, $formSectionFactory, $formFieldFactory);
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

    /**
     * @return BaseSectionInterface[]
     */
    public function getSections(): array
    {
        $sections = [
            $this->getFormSectionFactory()->create([
                'section_type' => 'card_section',
                'title' => 'General',
            ])->setFields($this->getGeneralFields()),
        ];
        return array_merge($sections, parent::getSections());
    }

    protected function getGeneralFields(): array
    {
        $fields = [
            $this->formFieldFactory->create([
                'label' => 'UUID',
                'name' => '_id',
                'is_readonly' => true,
            ]),
            $this->formFieldFactory->create([
                'label' => 'Name',
                'name' => 'name',
                'field_type' => 'text_field',
                'is_required' => true,
            ]),
            $this->formFieldFactory->create([
                'label' => 'Code',
                'name' => 'code',
                'field_type' => 'text_field',
                'is_required' => true,
            ]),
            $this->formFieldFactory->create([
                'label' => 'Status',
                'name' => 'status',
                'field_type' => 'select_field',
                'options' => [
                    $this->fieldOptionFactory->create(Option::class, [
                        'key' => 'enable',
                        'value' => 'Enable',
                    ]),
                    $this->fieldOptionFactory->create(Option::class, [
                        'key' => 'disable',
                        'value' => 'Disable',
                    ]),
                ],
                'sort_order' => 2,
            ]),
            $this->formFieldFactory->create([
                'label' => 'Permissions',
                'name' => 'permissions',
                'field_type' => 'tree_select_field',
                'options' => $this->getPermissionOptions(),
                'multi_select' => true,
                'is_searchable' => true,
            ]),
        ];

        $sortOrder = 5;
        foreach ($fields as $field) {
            $field->setSortOrder($sortOrder++);
        }

        return $fields;
    }
}
