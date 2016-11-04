<?php

class IzAfsluiting extends AppModel
{
    public $name = 'IzAfsluiting';
    public $displayField = 'naam';

    public $hasMany = array(

    );

    public $cachekey = 'IzAfsluitingList';
    public $active_key = 'IzAfsluitingListActive';

    public function beforeSave(&$model)
    {
        Cache::delete($this->cachekey);
        Cache::delete($this->active_key);

        return true;
    }
    public function afsluitingList($all = true)
    {
        $key = $this->cachekey;

        $conditions = array();

        if (empty($all)) {
            $key = $this->active_key;
            $conditions = array(
                'active' => true,
            );
        }

        $iz_afsluiting_list = Cache::read($key);

        if (!empty($iz_afsluiting_list)) {
            return $iz_afsluiting_list;
        }

        $iz_afsluiting_list = $this->find('list', array(
            'conditions' => $conditions,
            'contain' => array(),
        ));

        $iz_afsluiting_list = array('' => '') + $iz_afsluiting_list;

        Cache::write($key, $iz_afsluiting_list);

        return $iz_afsluiting_list;
    }
}
