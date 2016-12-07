<?php

class ZrmSetting extends AppModel
{
    public $name = 'ZrmSetting';
    public $displayField = 'request_module';

    public function update_table()
    {
        $settings = array(
            array(
                'id' => 1,
                'request_module' => 'Intake',
            ),
            array(
                'id' => 2,
                'request_module' => 'MaatschappelijkWerk',
            ),
            array(
                'id' => 3,
                'request_module' => 'Awbz',
            ),
            array(
                'id' => 4,
                'request_module' => 'Hi5',
            ),
            array(
                'id' => 5,
                'request_module' => 'GroepsactiviteitenIntake',
            ),
            array(
                'id' => 6,
                'request_module' => 'IzIntake',
            ),
        );

        $zrm_settings = $this->find('all');
        $ids = Set::ClassicExtract($zrm_settings, '{n}.ZrmSetting.id');

        $data = array();

        foreach ($settings as $setting) {
            if (in_array($setting['id'], $ids)) {
                continue;
            }

            $data[] = array(
                'ZrmSetting' => $setting,
            );
        }

        if (!empty($data)) {
            $this->saveAll($data);
        }
    }

    public function required_fields()
    {
        $zrm_settings = registry_get('ZrmSettings', 'ZrmSettings', true);

        if (!$zrm_settings) {
            $required_fields = array();
            $zrm_models = array();
            App::import('Model', 'ZrmReport');

            $zrm_report = new ZrmReport();
            $fields = $this->find('all');

            foreach ($fields as $k => $f) {
                $zrm_models[$f['ZrmSetting']['request_module']] = array();

                foreach ($zrm_report->zrm_items as $k => $v) {
                    if (!empty($f['ZrmSetting'][$k])) {
                        $required_fields[$f['ZrmSetting']['request_module']][] = $k;
                    }
                }
            }

            $zrm_settings = array(
                'required_fields' => $required_fields,
                'zrm_models' => $zrm_models,
            );

            registry_set('ZrmSettings', 'ZrmSettings', $zrm_settings, true);
        }

        return $zrm_settings;
    }

    public function clear_cache()
    {
        registry_delete('ZrmSettings', 'ZrmSettings', true);
    }
}
