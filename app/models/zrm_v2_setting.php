<?php

App::import('Model', 'ZrmSetting');

class ZrmV2Setting extends ZrmSetting
{
    public $name = 'ZrmV2Setting';

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
        $ids = Set::ClassicExtract($zrm_settings, '{n}.ZrmV2Setting.id');

        $data = [];

        foreach ($settings as $setting) {
            if (in_array($setting['id'], $ids)) {
                continue;
            }

            $data[] = [
                'ZrmV2Setting' => $setting,
            ];
        }

        if (!empty($data)) {
            $this->saveAll($data);
        }
    }

    public function required_fields()
    {
        $zrm_settings = registry_get('ZrmV2Settings', 'ZrmV2Settings', true);

        if (!$zrm_settings) {
            $required_fields = [];
            $zrm_models = [];
            App::import('Model', 'ZrmV2Report');
            $zrm_report = new ZrmV2Report();
            $fields = $this->find('all');

            foreach ($fields as $k => $f) {
                $zrm_models[$f['ZrmV2Setting']['request_module']] = [];
                foreach ($zrm_report->zrm_items as $k => $v) {
                    if (!empty($f['ZrmV2Setting'][$k])) {
                        $required_fields[$f['ZrmV2Setting']['request_module']][] = $k;
                    }
                }
            }

            $zrm_settings = [
                'required_fields' => $required_fields,
                'zrm_models' => $zrm_models,
            ];

            registry_set('ZrmV2Settings', 'ZrmV2Settings', $zrm_settings, true);
        }

        return $zrm_settings;
    }

    public function clear_cache()
    {
        registry_delete('ZrmV2Settings', 'ZrmV2Settings', true);
    }
}
