/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React from 'react';
import DataFormPageComponent from '@EveryWorkflow/DataFormBundle/Component/DataFormPageComponent';

const RoleFormPage = () => {
    return (
        <DataFormPageComponent
            title="Role"
            getPath="/auth/role/{uuid}"
            savePath="/auth/role/{uuid}"
        />
    );
};

export default RoleFormPage;
