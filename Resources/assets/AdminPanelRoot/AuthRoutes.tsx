/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import { lazy } from "react";

const RoleListPage = lazy(() => import("@EveryWorkflow/AuthBundle/Admin/Page/RoleListPage"));
const RoleFormPage = lazy(() => import("@EveryWorkflow/AuthBundle/Admin/Page/RoleFormPage"));

export const AuthRoutes = [
    {
        path: '/system/role',
        exact: true,
        component: RoleListPage
    },
    {
        path: '/system/role/create',
        exact: true,
        component: RoleFormPage
    },
    {
        path: '/system/role/:uuid/edit',
        exact: true,
        component: RoleFormPage
    },
];
