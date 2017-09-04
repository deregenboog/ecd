<?php

class IzOntstaanContact extends AppModel
{
    public $name = 'IzOntstaanContact';
    public $displayField = 'naam';

    public $cachekey = 'IzOntstaanContact';

    public function beforeSave(&$model)
    {
        Cache::delete($this->cachekey);

        return true;
    }

    public function ontstaanContactList($cur_id = null)
    {
        $iz_ontstaan_contact_list = Cache::read($this->cachekey);

        if (!empty($iz_ontstaan_contact_list) and empty($cur_id)) {
            return $iz_ontstaan_contact_list;
        }

        if (empty($iz_ontstaan_contact_list)) {
            $iz_ontstaan_contact_list = $this->find('list', [
                'conditions' => ['active' => 1],
            ]);
        }

        $iz_ontstaan_contact_list = ['' => ''] + $iz_ontstaan_contact_list;

        Cache::write($this->cachekey, $iz_ontstaan_contact_list);

        if (!empty($cur_id) && !array_key_exists($cur_id, $iz_ontstaan_contact_list)) {
            $o = $this->getById($cur_id);

            if (!empty($o)) {
                $iz_ontstaan_contact_list[$cur_id] = $o['naam'];
            }
        }

        return $iz_ontstaan_contact_list;
    }
}
