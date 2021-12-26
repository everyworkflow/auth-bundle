/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React, { lazy, useContext, useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import Form from 'antd/lib/form';
import Card from 'antd/lib/card';
import PanelContext from "@EveryWorkflow/PanelBundle/Context/PanelContext";
import DataFormInterface from "@EveryWorkflow/DataFormBundle/Model/DataFormInterface";
import { ACTION_SET_PAGE_TITLE } from "@EveryWorkflow/PanelBundle/Reducer/PanelReducer";
import AbstractFieldInterface from "@EveryWorkflow/DataFormBundle/Model/Field/AbstractFieldInterface";
import Remote from "@EveryWorkflow/PanelBundle/Service/Remote";
import PageHeaderComponent from "@EveryWorkflow/AdminPanelBundle/Component/PageHeaderComponent";
import BreadcrumbComponent from "@EveryWorkflow/AdminPanelBundle/Component/BreadcrumbComponent";
import DataFormComponent from "@EveryWorkflow/DataFormBundle/Component/DataFormComponent";
import { FORM_TYPE_HORIZONTAL } from "@EveryWorkflow/DataFormBundle/Component/DataFormComponent/DataFormComponent";
import AlertAction, { ALERT_TYPE_ERROR, ALERT_TYPE_SUCCESS } from "@EveryWorkflow/PanelBundle/Action/AlertAction";

const SUBMIT_SAVE_CHANGES = 'save_changes';
const SUBMIT_SAVE_CHANGES_AND_CONTINUE = 'save_changes_and_continue';

const RoleFormPage = () => {
    const { dispatch: panelDispatch } = useContext(PanelContext);
    const { uuid = '' }: { uuid: string | undefined } = useParams();
    const navigate = useNavigate();
    const [form] = Form.useForm();
    const [dataForm, setDataForm] = useState<DataFormInterface>();
    let submitAction: string | undefined = undefined;

    useEffect(() => {
        panelDispatch({
            type: ACTION_SET_PAGE_TITLE,
            payload: uuid !== '' ? 'Edit role' : 'Create role',
        });

        const handleResponse = (response: any) => {
            response.data_form.fields.forEach((item: AbstractFieldInterface) => {
                if (
                    item.name &&
                    response.item &&
                    Object.prototype.hasOwnProperty.call(response.item, item.name)
                ) {
                    item.value = response.item[item.name];
                }
            });
            console.log('response.data_form --:', response.data_form);
            setDataForm(response.data_form);
        };

        const fetchItem = async () => {
            try {
                const response: any = await Remote.get(
                    uuid !== '' ? '/auth/role/' + uuid : '/auth/role/create'
                );
                handleResponse(response);
            } catch (error: any) {
                AlertAction({
                    message: error.message,
                    title: 'Fetch error',
                    type: ALERT_TYPE_ERROR,
                });
            }
        };

        fetchItem();
    }, [panelDispatch, uuid]);

    const onSubmit = async (data: any) => {
        const submitData: any = {};
        Object.keys(data).forEach(name => {
            if (data[name]) {
                submitData[name] = data[name];
            }
        });

        const handlePostResponse = (response: any) => {
            if (response.message) {
                AlertAction({
                    message: response.message,
                    title: 'Form submit success',
                    type: ALERT_TYPE_SUCCESS,
                });
            }
            if (submitAction === SUBMIT_SAVE_CHANGES) {
                navigate(-1);
            }
        };

        try {
            const response = await Remote.post(
                uuid !== '' ? '/auth/role/' + uuid : '/auth/role/create',
                submitData
            );
            handlePostResponse(response);
        } catch (error: any) {
            AlertAction({
                message: error.message,
                title: 'Submit error',
                type: ALERT_TYPE_ERROR,
            });
        }
    };

    return (
        <>
            <PageHeaderComponent
                title={uuid !== '' ? `ID: ${uuid}` : undefined}
                actions={[
                    {
                        label: 'Save changes',
                        onClick: () => {
                            submitAction = SUBMIT_SAVE_CHANGES;
                            form.submit();
                        },
                    },
                    {
                        label: 'Save and continue',
                        onClick: () => {
                            submitAction = SUBMIT_SAVE_CHANGES_AND_CONTINUE;
                            form.submit();
                        },
                    },
                ]}
            />
            <BreadcrumbComponent />
            <Card
                className="app-container"
                title={'General'}
                style={{ marginBottom: 24 }}>
                {dataForm && (
                    <DataFormComponent
                        form={form}
                        formData={dataForm}
                        formType={FORM_TYPE_HORIZONTAL}
                        onSubmit={onSubmit}
                        formFieldMaps={{
                          permissions: lazy(
                            () => import('@EveryWorkflow/DataFormBundle/Field/MultiSelect')
                          )
                        //   permissions: lazy(
                        //     () => import('@EveryWorkflow/AuthBundle/Admin/Page/RoleFormPage/PermissionsField')
                        //   )
                        }}
                    />
                )}
            </Card>
        </>
    );
};

export default RoleFormPage;
