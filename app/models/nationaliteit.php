<?php

class Nationaliteit extends AppModel
{
    public $name = 'Nationaliteit';
    public $displayField = 'naam';
    public $order = array('id');

    public $hasMany = array(
        'Klant' => array(
            'className' => 'Klant',
            'foreignKey' => 'nationaliteit_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
    );

    public $cachekey = 'NationaliteitenList';

    public function beforeSave($options = array())
    {
        Cache::delete($this->cachekey);

        return parent::beforeSave($options);
    }

    public function findList()
    {
        $nationaliteiten = Cache::read($this->cachekey);

        if (!empty($nationaliteiten)) {
            return $nationaliteiten;
        }
        $nationaliteiten = $this->find('list');
        Cache::write($this->cachekey, $nationaliteiten);

        return $nationaliteiten;
    }
}
