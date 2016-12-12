<?php

class GroepsactiviteitenAfsluiting extends AppModel
{
    public $name = 'GroepsactiviteitenAfsluiting';
    public $displayField = 'naam';

    public $actAs = array(
        'Containable',
    );

    public $validate = array(
            'naam' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een afsluiting in',
                            'allowEmpty' => false,
                            'required' => true,
                    ),
            ),
    );

    public $list_cache_key = 'GroepsactiviteitenAfsluting.list_cache_key';

    public function get_groepsactiviteiten_afsluiting()
    {
        $groepsactiviteiten_afsluiting = Cache::read($this->list_cache_key);

        if (!empty($groepsactiviteiten_afsluiting)) {
            return $groepsactiviteiten_afsluiting;
        }

        $this->recursive = -1;
        $groepsactiviteiten_afsluiting = $this->find('all', array(
                'contain' => [],
        ));
        Cache::write($this->list_cache_key, $groepsactiviteiten_afsluiting);

        return $groepsactiviteiten_afsluiting;
    }

    public function get_groepsactiviteiten_afsluiting_list()
    {
        $groepsactiviteiten_afsluiting = $this->get_groepsactiviteiten_afsluiting();

        $groepsactiviteiten_afsluiting_list = [];
        foreach ($groepsactiviteiten_afsluiting as $r) {
            $groepsactiviteiten_afsluiting_list[$r['GroepsactiviteitenAfsluiting']['id']] = $r['GroepsactiviteitenAfsluiting']['naam'];
        }

        return $groepsactiviteiten_afsluiting_list;
    }
}
