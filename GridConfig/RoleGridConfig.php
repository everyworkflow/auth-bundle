<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\GridConfig;

use EveryWorkflow\CoreBundle\Model\DataObjectInterface;
use EveryWorkflow\DataGridBundle\BulkAction\ButtonBulkAction;
use EveryWorkflow\DataGridBundle\Factory\ActionFactoryInterface;
use EveryWorkflow\DataGridBundle\HeaderAction\ButtonHeaderAction;
use EveryWorkflow\DataGridBundle\Model\DataGridConfig;
use EveryWorkflow\DataGridBundle\RowAction\ButtonRowAction;

class RoleGridConfig extends DataGridConfig implements RoleGridConfigInterface
{
    protected const GRID_COLUMNS =
    [
        '_id',
        'code',
        'name',
        'status',
        'created_at',
        'updated_at'
    ];
    
    public function __construct(DataObjectInterface $dataObject, ActionFactoryInterface $actionFactory)
    {
        parent::__construct($dataObject, $actionFactory);
        $this->dataObject->setDataIfNot(self::KEY_IS_FILTER_ENABLED, true);
        $this->dataObject->setDataIfNot(self::KEY_IS_COLUMN_SETTING_ENABLED, true);
    }

    public function getActiveColumns(): array
    {
        return array_merge(self::GRID_COLUMNS, parent::getActiveColumns());
    }

    public function getSortableColumns(): array
    {
        return array_merge(self::GRID_COLUMNS, parent::getSortableColumns());
    }

    public function getFilterableColumns(): array
    {
        return array_merge(self::GRID_COLUMNS, parent::getFilterableColumns());
    }

    public function getHeaderActions(): array
    {
        return array_merge([
            $this->getActionFactory()->create(ButtonHeaderAction::class, [
                'button_label' => 'Create new',
                'button_path' => '/system/role/create',
                'button_type' => 'primary',
            ]),
        ], parent::getHeaderActions());
    }

    public function getRowActions(): array
    {
        return array_merge([
            $this->getActionFactory()->create(ButtonRowAction::class, [
                'button_label' => 'Edit',
                'button_path' => '/system/role/{_id}/edit',
                'button_type' => 'primary',
            ]),
            $this->getActionFactory()->create(ButtonRowAction::class, [
                'button_label' => 'Delete',
                'button_path' => '/auth/role/{_id}',
                'button_type' => 'primary',
                'path_type' => ButtonRowAction::PATH_TYPE_DELETE_CALL,
                'is_danger' => true,
                'is_confirm' => true,
                'confirm_message' => 'Are you sure, you want to delete this item?',
            ]),
        ], parent::getBulkActions());
    }


    public function getBulkActions(): array
    {
        $bulkActions = [
            $this->getActionFactory()->create(ButtonBulkAction::class, [
                'button_label' => 'Enable',
                'button_path' => '/auth/role/bulk-action/enable',
                'button_type' => 'default',
                'path_type' => ButtonBulkAction::PATH_TYPE_POST_CALL,
            ]),
            $this->getActionFactory()->create(ButtonBulkAction::class, [
                'button_label' => 'Disable',
                'button_path' => '/auth/role/bulk-action/disable',
                'button_type' => 'default',
                'path_type' => ButtonBulkAction::PATH_TYPE_POST_CALL,
            ]),
        ];
        return array_merge($bulkActions, parent::getBulkActions());
    }
}
