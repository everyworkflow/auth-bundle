<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\GridConfig;

use EveryWorkflow\DataGridBundle\Model\Action\ButtonAction;
use EveryWorkflow\DataGridBundle\Model\Action\ConfirmedActionButton;
use EveryWorkflow\DataGridBundle\Model\DataGridConfig;

class RoleGridConfig extends DataGridConfig implements RoleGridConfigInterface
{
    protected const GRID_COLUMNS =
    [
        '_id',
        'code',
        'name',
        'created_at',
        'updated_at'
    ];

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
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Create new role',
                'path' => '/system/role/create',
            ]),
        ], parent::getHeaderActions());
    }

    public function getRowActions(): array
    {
        return array_merge([
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Edit',
                'path' => '/system/role/{_id}/edit',
            ]),
            $this->getActionFactory()->create(ConfirmedActionButton::class, [
                'label' => 'Delete',
                'path' => '/system/role/{_id}/delete',
                'confirm_message' => 'Are you sure, you want to delete this item?',
            ]),
        ], parent::getBulkActions());
    }


    public function getBulkActions(): array
    {
        return array_merge([
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Enable',
                'path' => '/system/role/enable/{_id}',
            ]),
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Disable',
                'path' => '/system/role/disable/{_id}',
            ]),
            $this->getActionFactory()->create(ConfirmedActionButton::class, [
                'label' => 'Delete',
                'path' => '/system/role/delete/{_id}',
                'confirm_message' => 'Are you sure, you want to delete all selected item(s)?',
            ]),
        ], parent::getBulkActions());
    }
}
