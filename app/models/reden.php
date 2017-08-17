<?php

class Reden extends AppModel
{
    public $name = 'Reden';
    public $displayField = 'naam';

    public $violent = [
        'Fysieke agressie',
        'Verbale agressie',
    ];

    public $hasAndBelongsToMany = [
        'Schorsing' => [
            'className' => 'Schorsing',
            'joinTable' => 'schorsingen_redenen',
            'foreignKey' => 'reden_id',
            'associationForeignKey' => 'schorsing_id',
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

    public function get_schorsing_redenen()
    {
        $cacheKey = 'Schorsing_get_schorsing_redenen';
        $redenen = Cache::read($cacheKey);

        if (!empty($redenen)) {
            return $redenen;
        }

        $redenen = $this->find('list');
        Cache::write($cacheKey, $redenen);

        return $redenen;
    }

    public function get_violent_options()
    {
        $redenen = $this->get_schorsing_redenen();

        $violent_options = [];

        foreach ($redenen as $key => $reden) {
            if (in_array($reden, $this->violent)) {
                $violent_options[] = $key;
            }
        }

        return $violent_options;
    }
}
