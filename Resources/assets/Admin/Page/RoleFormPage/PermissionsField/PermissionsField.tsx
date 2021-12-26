/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React from 'react';
import Form from 'antd/lib/form';
import Input from 'antd/lib/input';
import DynamicFieldPropsInterface from "@EveryWorkflow/DataFormBundle/Model/DynamicFieldPropsInterface";
import TextFieldInterface from "@EveryWorkflow/DataFormBundle/Model/Field/TextFieldInterface";
import {
    FORM_MODE_EDIT,
    FORM_MODE_VIEW
} from "@EveryWorkflow/DataFormBundle/Component/DataFormComponent/DataFormComponent";

interface PermissionsFieldProps extends DynamicFieldPropsInterface {
    fieldData: TextFieldInterface;
}

const PermissionsField = ({ fieldData, mode = FORM_MODE_EDIT, onChange, children }: PermissionsFieldProps) => {
    console.log('PermissionsField fieldData -->', fieldData);
    if (mode === FORM_MODE_VIEW) {
        return <span>{fieldData?.value}</span>;
    }

    const getInputType = () => {
        return fieldData.input_type ? fieldData.input_type : 'text';
    };

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        if (onChange) {
            onChange(event.target.value);
        }
    };

    return (
        <>
            <Form.Item
                name={fieldData.name}
                label={'New Form Field for name only - ' + fieldData.label}
                initialValue={fieldData.value ?? ''}
                rules={[{ required: fieldData.is_required }]}
            >
                <Input
                    onChange={handleChange}
                    allowClear={fieldData.allow_clear}
                    addonBefore={fieldData.prefix_tab_text}
                    addonAfter={fieldData.suffix_tab_text}
                    prefix={fieldData.prefix_text}
                    suffix={fieldData.suffix_text}
                    type={(fieldData.is_disabled || fieldData.is_readonly) ? 'text' : getInputType()}
                    disabled={fieldData.is_disabled}
                    bordered={!fieldData.is_readonly}
                    readOnly={fieldData.is_readonly}
                />
            </Form.Item>
            {children}
        </>
    );
};

export default PermissionsField;
