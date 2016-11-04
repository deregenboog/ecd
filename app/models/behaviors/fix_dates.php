<?php

class FixDatesBehavior extends ModelBehavior
{
    public function beforeSave(&$model)
    {
        $modelname = $model->alias;
        foreach ($model->data[$modelname] as $fieldname => $val) {
            //checking if the field can be found in the models _schema
            if (array_key_exists($fieldname, $model->_schema)) {
                //getting the field type
                $field_type = $model->_schema[$fieldname]['type'];
                if (empty($val) && $field_type == 'date') {
                    unset($model->data[$modelname][$fieldname]);
                }
            }
        }

        return true;
    }
}
