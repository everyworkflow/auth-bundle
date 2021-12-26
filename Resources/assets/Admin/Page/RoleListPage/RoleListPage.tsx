/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React, {useContext, useEffect} from 'react';
import {useLocation} from "react-router-dom";
import PanelContext from "@EveryWorkflow/PanelBundle/Context/PanelContext";
import {ACTION_SET_PAGE_TITLE} from "@EveryWorkflow/PanelBundle/Reducer/PanelReducer";
import DataGridComponent from "@EveryWorkflow/DataGridBundle/Component/DataGridComponent";
import {DATA_GRID_TYPE_PAGE} from "@EveryWorkflow/DataGridBundle/Component/DataGridComponent/DataGridComponent";

const RoleListPage = () => {
    const {dispatch: panelDispatch} = useContext(PanelContext);
    const location = useLocation();

    useEffect(() => {
        panelDispatch({type: ACTION_SET_PAGE_TITLE, payload: 'Role list'});
    }, [panelDispatch]);

    return (
        <>
            <DataGridComponent
                dataGridUrl={'/auth/role' + location.search}
                dataGridType={DATA_GRID_TYPE_PAGE}
            />
        </>
    );
};

export default RoleListPage;
