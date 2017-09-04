<?php

class IzProject extends AppModel
{
    public $name = 'IzProject';
    public $displayField = 'naam';

    public $actsAs = [
            'Containable',
    ];

    public $hasAndBelongsToMany = [
        'IzDeelnemer' => [
            'className' => 'IzDeelnemer',
            'joinTable' => 'iz_deelnemers_iz_projecten',
            'foreignKey' => 'iz_project_id',
            'associationForeignKey' => 'iz_deelnemer_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ],
    ];

    public function beforeSave(&$model)
    {
        Cache::delete($this->getcachekey(false));
        Cache::delete($this->getcachekey(true));

        return true;
    }

    public function getcachekey($all = true)
    {
        $cachekey = 'IzProjectenList';

        if ($all) {
            return $cachekey;
        }

        $cachekey .= date('Y-m-d');

        return $cachekey;
    }

    public function getProjects()
    {
        $projects = $this->find('all', [
            'contain' => [],
        ]);

        return $projects;
    }

    public function projectLists($all = false)
    {
        $cachekey = $this->getcachekey($all);
        $projectlists = Cache::read($cachekey);

        if (!empty($projectlists)) {
            return $projectlists;
        }

        if ($all) {
            $conditions = [];
        } else {
            $conditions = [
                'OR' => [
                    [
                        'startdatum <= now()',
                        'einddatum >= now()',
                    ],
                    [
                        'startdatum <= now()',
                        'einddatum' => null,
                    ],
                ],
             ];
        }

        $projectlists = $this->find('list', [
            'conditions' => $conditions,
            'order' => 'naam asc',
        ]);

        Cache::write($cachekey, $projectlists);

        return $projectlists;
    }
}
