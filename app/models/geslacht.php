<?php

class Geslacht extends AppModel
{
    public $name = 'Geslacht';
    public $displayField = 'volledig';
    public $order = 'Geslacht.id DESC';

    public $cachekey = 'GeslachtenList';

    public function beforeSave($options = [])
    {
        Cache::delete($this->cachekey);

        return parent::beforeSave($options);
    }

    public function findList()
    {
        $geslachten = Cache::read($this->cachekey);

        if (!empty($geslachten)) {
            return $geslachten;
        }
        $geslachten = $this->find('list');
        Cache::write($this->cachekey, $geslachten);

        return $geslachten;
    }
}
