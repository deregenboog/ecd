<?php

class schorsing extends AppModel
{
    public $name = 'Schorsing';
    public $validate = array(
        'datum_van' => array(
            'date' => array(
                'rule' => array('date'),
            ),
        ),
        'datum_tot' => array(
            'date' => array(
                'rule' => array('date'),
            ),
        ),
        'klant_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'remark' => array(
            'rule' => array('check_agressie'),
            'message' => 'Reden verplicht bij agressie!',
        ),
        'agressie' => array(
                'rule' => array('check_for_doelwit'),
                'message' => 'Aangeven tegen welke medewerker(s) de agressie is gericht!',
        ),
        'aggressie_doelwit' => array(
            'rule' => array('check_doelwit'),
            'message' => 'Aangeven wat de functie van de medewerker is!',
        ),
            'aggressie_tegen_medewerker' => array(
                    'rule' => array('check_dummy'),
                    'message' => 'Aangeven (ja / nee ) of de agressie is gericht tegen een medewerker!',
            ),
            'aggressie_doelwit2' => array(
                    'rule' => array('check_doelwit'),
                    'message' => 'Aangeven wat de functie van de medewerker is!',
            ),
            'aggressie_tegen_medewerker2' => array(
                    'rule' => array('check_dummy'),
                    'message' => 'Aangeven (ja / nee ) of de agressie is gericht tegen een medewerker!',
            ),
            'aggressie_doelwit3' => array(
                    'rule' => array('check_doelwit'),
                    'message' => 'Aangeven wat de functie van de medewerker is!',
            ),
            'aggressie_tegen_medewerker3' => array(
                    'rule' => array('check_dummy'),
                    'message' => 'Aangeven (ja / nee ) of de agressie is gericht tegen een medewerker!',
            ),
            'aggressie_doelwit4' => array(
                    'rule' => array('check_doelwit'),
                    'message' => 'Aangeven wat de functie van de medewerker is!',
            ),
            'aggressie_tegen_medewerker4' => array(
                    'rule' => array('check_dummy'),
                    'message' => 'Aangeven (ja / nee ) of de agressie is gericht tegen een medewerker!',
            ),
        'aangifte' => array(
            'rule' => array('check_dummy'),
            'message' => 'Aangeven of aangifte is gedaan (ja / nee ) is verplicht bij agressie!',
        ),
        'nazorg' => array(
            'rule' => array('check_dummy'),
            'message' => 'Aangeven of nazorg nogig (ja / nee) is verplicht bij agressie!',
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'Locatie' => array(
            'className' => 'Locatie',
            'foreignKey' => 'locatie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Klant' => array(
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public $hasAndBelongsToMany = array(
        'Reden' => array(
            'className' => 'Reden',
            'joinTable' => 'schorsingen_redenen',
            'foreignKey' => 'schorsing_id',
            'associationForeignKey' => 'reden_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ),
    );

    public $actsAs = array('Containable');
    public $contain = array(
        'Locatie' => array('fields' => array('naam')),
        'Reden' => array('fields' => array('naam')),
    );

    public function check_dummy($field)
    {
        return true;
    }

    public function check_for_doelwit($field)
    {
        if (! $this->is_violent()) {
            return true;
        }
        if (empty($this->data['Schorsing']['agressie'])) {
            return true;
        }
        if (empty($this->data['Schorsing']['aggressie_doelwit']) &&
                empty($this->data['Schorsing']['aggressie_doelwit2']) &&
                empty($this->data['Schorsing']['aggressie_doelwit3']) &&
                empty($this->data['Schorsing']['aggressie_doelwit4'])) {
            return false;
        }
        return true;
    }

    public function check_doelwit($field)
    {
        reset($field);
        $first_key = key($field);
        $ext = preg_replace('/aggressie_doelwit/', '', $first_key);

        if (!empty($this->data['Schorsing']['aggressie_doelwit'.$ext])) {
            if (empty($this->data['Schorsing']['aggressie_tegen_medewerker'.$ext])) {
                return false;
            }
        }
        return true;
    }

    public function check_agressie()
    {
        if (! $this->is_violent()) {
            return true;
        }
        if (empty($this->data['Schorsing']['remark'])) {
            return false;
        }
        return true;
    }

    public function is_violent()
    {
        $first_key = 'remark';
        $reden = $this->data['Reden']['Reden'];
        $violent_options = $this->Reden->get_violent_options();
        if (empty($reden)) {
            return false;
        }

        foreach ($reden as $r) {
            if (in_array($r, $violent_options)) {
                return true;
            }
        }
        return false;
    }

    /*
     * checks if there's at least one Reden
    */
    public function beforeValidate($options)
    {
        //for the gezien field do not validate Reden
        if (
            empty($options['fieldList']) ||
            $options['fieldList'] != array('gezien')
        ) {
            if (
                (!isset($this->data['Reden']['Reden'])
                || empty($this->data['Reden']['Reden']))
            ) {
                $this->invalidate('non_existent_field'); // fake validation error on Schorsing
                    $this->Reden->invalidate('Reden', 'Selecteer minstens één optie');
            }
        }
        return true;
    }

    public function getActiveSchorsingen($klant_id)
    {
        $conditions = array(
            'Schorsing.klant_id' => $klant_id,
            'Schorsing.datum_tot >=' => date('Y-m-d'),
        );
        return $this->find('all', array(
            'conditions' => $conditions,
            'contain' => $this->contain,
        ));
    }//getActiveSchorsingen

    /**
     * Calculates the expiry date of the last active schorsing.
     * @param int $klant_id
     * @return string
     */
    public function getLastActiveSchorsingExpiry($klant_id, $locatie_id)
    {
        $result = $this->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'Schorsing.klant_id' => $klant_id,
                'Schorsing.datum_tot >=' => date('Y-m-d'),
                'Schorsing.locatie_id' => $locatie_id,
            ),
            'fields' => array(
                'max(Schorsing.datum_tot) max_expiry',
            ),
        ));
        return $result[0]['max_expiry'];
    }

    public function getLastActiveSchorsing($klant_id)
    {
        $result = $this->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'Schorsing.klant_id' => $klant_id,
                'Schorsing.datum_tot >=' => date('Y-m-d'),
            ),
            'order' => 'Schorsing.datum_tot DESC',
            'fields' => array(
                'locatie_id',
                'datum_tot',
                'datum_van',
            ),
        ));
        return $result;
    }

    public function getExpiredSchorsingen($klant_id)
    {
        $conditions = array(
            'Schorsing.klant_id' => $klant_id,
            'Schorsing.datum_tot <' => date('Y-m-d'),
        );
        return $this->find('all', array(
            'conditions' => $conditions,
            'contain' => $this->contain,
        ));
    }//getExpiredSchorsingen

    public function countActiveSchorsingen($klant_id, $locatie_id = null)
    {
        $c = array(
            'Schorsing.datum_tot >=' => date('Y-m-d'),
            'Schorsing.klant_id' => $klant_id,
        );

        if ($locatie_id) {
            $c['Schorsing.locatie_id'] = $locatie_id;
        }
        return $this->find('count', array('conditions' => $c));
    }

    //returns 0 if there are expired and not seen Schorsingen
    //returns null if there are no expired Schorsingen at all
    public function countUnSeenSchorsingen($klant_id)
    {
        $opt = array('conditions' => array(
            'Schorsing.datum_tot <' => date('Y-m-d'),
            'Schorsing.klant_id' => $klant_id,
        ));
        if ($this->find('count', $opt) > 0) {
            $opt['conditions']['Schorsing.gezien'] = 0;
            return $this->find('count', $opt);
        } else {
            return null;
        }
    }

    public function get_schorsing_messages(&$data, $locatie_id = null)
    {
        if (empty($data) || !is_array($data)) {
            return;
        }

        foreach ($data as $key => $klant) {
            $return  = $this->get_schorsing_msg($klant['Klant']['id'], $locatie_id);
            $data[$key]['Klant'] = array_merge($klant['Klant'], $return);
        }
    }

    public function calculateDates(&$data)
    {
        if (!isset($data['Schorsing']['days'])) {
            return false;
        } else {
            if ($data['Schorsing']['days'] == -1) {
                if (!isset($data['Schorsing']['datum_tot']) || empty($data['Schorsing']['datum_tot'])) {
                    return false;
                } else {
                    return true;
                }
            } else {
                $days = $data['Schorsing']['days'];
            }
            
            if (!isset($data['Schorsing']['datum_van'])) {
                $van = $data['Schorsing']['datum_van'] = date('Y-m-d');
            } else {
                $van = $data['Schorsing']['datum_van'];
            }
            
            $data['Schorsing']['datum_tot'] =
                date('Y-m-d', strtotime("+ $days days", strtotime($van)));
            
            unset($data['Schorsing']['days']);
            unset($data['Schorsing']['more_days']);
            return true;
        }
    }
    
    public function countActiveSchorsingenMsg($klant_id)
    {
        $schCount = $this->countActiveSchorsingen($klant_id);
        if ($schCount == 0) {
            $unseenSch = $this->countUnSeenSchorsingen($klant_id);
            if ($unseenSch > 0) {
                $schorsing = _('schorsing verlopen');
            } else {
                $schorsing    = _('geen');
            }
        } elseif ($schCount == 1) {
            $schorsing    = '1 '._('schorsing');
        } else {
            $schorsing    = $schCount.' '._('schorsingen');
        }
        return $schorsing;
    }

    public function get_schorsing_msg($klant_id, $locatie_id = null)
    {
        $result = array(
            'schorsingen' => null,
            'schorsing_datum_van' => null,
            'schorsing_datum_tot' => null,
            'schorsing_locatie_id' => null,
        );
        
        $result['schorsingen'] = $this->countActiveSchorsingenMsg($klant_id);

        if ($locatie_id) {
            $lastschorsing = $this->getLastActiveSchorsing($klant_id, $locatie_id);
            if ($lastschorsing) {
                $result['schorsing_locatie_id'] = $lastschorsing['Schorsing']['locatie_id'];
                $result['schorsing_datum_van'] = $lastschorsing['Schorsing']['datum_van'];
                $result['schorsing_datum_tot'] = $lastschorsing['Schorsing']['datum_tot'];
            }
        }
        
        return $result;
    }
}
