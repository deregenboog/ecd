<?php

class land extends AppModel
{
    public $name = 'Land';

    public $displayField = 'land';

    public $cachekey = 'LandenList';

    public function beforeSave($options = array())
    {
        Cache::delete($this->cachekey);

        return parent::beforeSave($options);
    }

    public function findList()
    {
        $landen = Cache::read($this->cachekey);

        if (!empty($landen)) {
            return $landen;
        }

        $landen = $this->find('list');

        Cache::write($this->cachekey, $landen);

        return $landen;
    }
}
