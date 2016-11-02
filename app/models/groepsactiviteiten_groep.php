<?php

class GroepsactiviteitenGroep extends AppModel
{
    public $name = 'GroepsactiviteitenGroep';
    public $displayField = 'naam';

    public $actsAs = array('Containable');

    public $validate = array(
            'naam' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een reden in',
                            'allowEmpty' => false,
                            'required' => true,
                    ),
            ),
            'werkgebied' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een werkgebied in',
                            'allowEmpty' => false,
                            'required' => true,
                    ),
            ),
            'startdatum' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een startdatum in',
                            'allowEmpty' => false,
                            'required' => true,
                    ),
            ),
            'einddatum' => array(
                    'datecompare' => array(
                            'rule' => array(
                                    'compareDates',
                            ),
                            'message' => 'Einddatum moet later dan startdatum zijn',
                    ),
            ),

    );

    public function compareDates()
    {
        if (empty($this->data['GroepsactiviteitenGroep']['einddatum'])) {
            return true;
        }
        
        if (empty($this->data['GroepsactiviteitenGroep']['startdatum'])) {
            return true;
        }
        
        $s=strtotime($this->data['GroepsactiviteitenGroep']['startdatum']);
        $e=strtotime($this->data['GroepsactiviteitenGroep']['einddatum']);

        if ($e < $s) {
            return false;
        }
        
        return true;
    }

    public $hasMany = array(
        'Groepsactiviteit' => array(
            'className' => 'Groepsactiviteit',
            'foreignKey' => 'groepsactiviteiten_groep_id',
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
        'GroepsactiviteitenGroepenVrijwilliger' => array(
            'className' => 'GroepsactiviteitenGroepenVrijwilliger',
            'foreignKey' => 'groepsactiviteiten_groep_id',
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
        'GroepsactiviteitenGroepenKlant' => array(
             'className' => 'GroepsactiviteitenGroepenKlant',
             'foreignKey' => 'groepsactiviteiten_groep_id',
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

    public $list_cache_key = 'GroepsactiviteitenGroep.list_cache_key';
    public $list_cache_key_all = 'GroepsactiviteitenGroep.list_cache_key_all';

    public function save($data = null, $validate = true, $fieldList = array())
    {
        Cache::delete($this->list_cache_key);
        Cache::delete($this->list_cache_key_all);
        return parent::save($data, $validate, $fieldList);
    }

    private function in_range($data)
    {
        $now=time();
        $sd = strtotime($data['startdatum']);
        $ed = strtotime($data['einddatum']);
        
        if ($now < $sd) {
            $inperiod = false;
        } else {
            if (! empty($ed)) {
                if ($now < $ed) {
                    $inperiod = true;
                } else {
                    $inperiod = false;
                }
            } else {
                $inperiod = true;
            }
        }
        return $inperiod;
    }

    public function get_group_selection($data, $groepsactiviteiten_groep, $active = true)
    {
        $result = array();

        $cnt = 0;

        foreach ($data as $gv) {
            $inperiod = $this->in_range($gv);
            if (empty($gv['einddatum'])) {
                $inperiod = true;
            }

            if ($inperiod) {
                foreach ($groepsactiviteiten_groep as $ga) {
                    if ($ga['GroepsactiviteitenGroep']['id'] == $gv['groepsactiviteiten_groep_id']) {
                        $inperiod =$this->in_range($ga['GroepsactiviteitenGroep']);
                        break;
                    }
                }
            }
            
            if ($active && $inperiod || !$active && !$inperiod) {
                $result[] = $gv;
            }
        }
        return $result;
    }
    
    public function get_non_selected_open_groups($active_groups, $groepsactiviteiten_groep)
    {
        $now=time();
        $result = array();
        
        $tmp = Set::classicExtract($active_groups, '{n}.groepsactiviteiten_groep_id');
        
        foreach ($groepsactiviteiten_groep as $gr) {
            $inperiod =$this->in_range($gr['GroepsactiviteitenGroep']);

            if (! $inperiod) {
                continue;
            }
            if ($inperiod && in_array($gr['GroepsactiviteitenGroep']['id'], $tmp)) {
                continue;
            }
            $result[$gr['GroepsactiviteitenGroep']['id']] = $gr['GroepsactiviteitenGroep']['naam']." (".$gr['GroepsactiviteitenGroep']['werkgebied'].') ';
        }
        
        return $result;
    }

    public function get_groepsactiviteiten_groep($all = false)
    {
        $groepsactiviteiten_groep=null;
        $key = $this->list_cache_key;
        
        if (!empty($all)) {
            $key = $this->list_cache_key_all;
        }
        
        $groepsactiviteiten_groep = Cache::read($key);

        if (! empty($groepsactiviteiten_groep)) {
            return $groepsactiviteiten_groep;
        }

        $conditions = array();
        
        if (empty($all)) {
            $conditions = array(
                'OR' => array(
                    'einddatum ' => null,
                    'einddatum > ' => date('Y-m-d'),
                ),
                'activiteiten_registreren' => 1,
            );
        }

        $groepsactiviteiten_groep = $this->find('all', array(
            'conditions' => $conditions,
            'contain' => array(),
            'order' => 'naam asc',
        ));
        
        Cache::write($key, $groepsactiviteiten_groep);

        return $groepsactiviteiten_groep;
    }

    public function get_groepsactiviteiten_list($all = false)
    {
        $groepsactiviteiten_groep = $this->get_groepsactiviteiten_groep($all);

        $groepsactiviteiten_list = array();
        
        foreach ($groepsactiviteiten_groep as $gr) {
            $groepsactiviteiten_list[$gr['GroepsactiviteitenGroep']['id']] = $gr['GroepsactiviteitenGroep']['naam']." (".$gr['GroepsactiviteitenGroep']['werkgebied'].') ';
        }
        
        return $groepsactiviteiten_list;
    }
}
