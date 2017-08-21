<?php

class IzViaPersoon extends AppModel
{
    public $name = 'IzViaPersoon';
    public $displayField = 'naam';

    public $cachekey = 'IzViaPersoon';
    public $cachekeyall = 'IzViaPersoonAll';

    public function beforeSave(&$model)
    {
        Cache::delete($this->cachekey);

        return true;
    }

    public function viaPersoon($cur_id = null)
    {
        $iz_via_persoon_list = Cache::read($this->cachekey);

        if (!empty($iz_via_persoon_list) && empty($cur_id)) {
            return $iz_via_persoon_list;
        }

        if (empty($iz_via_persoon_list)) {
            $iz_via_persoon_list = $this->find('list', [
                'conditions' => ['active' => 1],
            ]);
        }

        $iz_via_persoon_list = ['' => ''] + $iz_via_persoon_list;

        Cache::write($this->cachekey, $iz_via_persoon_list);

        if (!empty($cur_id) && !array_key_exists($cur_id, $iz_via_persoon_list)) {
            $i = $this->getById($cur_id);
            if (!empty($i)) {
                $iz_via_persoon_list[$cur_id] = $i['naam'];
            }
        }

        return $iz_via_persoon_list;
    }
}
