<?php

class ZrmSetting extends AppModel
{
    public $name = 'ZrmSetting';
    public $displayField = 'request_module';

    public function update_table()
    {
        $settings = [
            [
                'id' => 1,
                'request_module' => 'Intake',
            ],
            [
                'id' => 2,
                'request_module' => 'MaatschappelijkWerk',
            ],
            [
                'id' => 3,
                'request_module' => 'Awbz',
            ],
            [
                'id' => 4,
                'request_module' => 'Hi5',
            ],
            [
                'id' => 5,
                'request_module' => 'GroepsactiviteitenIntake',
            ],
            [
                'id' => 6,
                'request_module' => 'IzIntake',
            ],
        ];

        $zrm_settings = $this->find('all');
        $ids = Set::ClassicExtract($zrm_settings, '{n}.ZrmSetting.id');

        $data = [];

        foreach ($settings as $setting) {
            if (in_array($setting['id'], $ids)) {
                continue;
            }

            $data[] = [
                'ZrmSetting' => $setting,
            ];
        }

        if (!empty($data)) {
            $this->saveAll($data);
        }
    }

    public function required_fields()
    {
        $zrm_settings = registry_get('ZrmSettings', 'ZrmSettings', true);

        if (!$zrm_settings) {
            $required_fields = [];
            $zrm_models = [];
            App::import('Model', 'ZrmReport');

            $zrm_report = new ZrmReport();
            $fields = $this->find('all');

            foreach ($fields as $k => $f) {
                $zrm_models[$f['ZrmSetting']['request_module']] = [];

                foreach ($zrm_report->zrm_items as $k => $v) {
                    if (!empty($f['ZrmSetting'][$k])) {
                        $required_fields[$f['ZrmSetting']['request_module']][] = $k;
                    }
                }
            }

            $zrm_settings = [
                'required_fields' => $required_fields,
                'zrm_models' => $zrm_models,
            ];

            registry_set('ZrmSettings', 'ZrmSettings', $zrm_settings, true);
        }

        return $zrm_settings;
    }

    public function clear_cache()
    {
        registry_delete('ZrmSettings', 'ZrmSettings', true);
    }
}
