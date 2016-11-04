<?php

class klant extends AppModel
{
    public $name = 'Klant';
    public $order = 'Klant.achternaam ASC';

    public $virtualFields = array(

            'name' => "CONCAT_WS(' ', `Klant`.`voornaam`, `Klant`.`tussenvoegsel`, `Klant`.`achternaam`)",
            'name1st_part' => "CONCAT_WS(' ', `Klant`.`voornaam`, `Klant`.`roepnaam`)",
            'name2nd_part' => "CONCAT_WS(' ', `Klant`.`tussenvoegsel`, `Klant`.`achternaam`)",
            'klant_nummer' => "CONCAT('K',`Klant`.`id`)",

    );

    public $displayField = 'name';
    public $showDisabled = false;

    public $watchfields = array(
        'voornaam', 'tussenvoegsel', 'achternaam', 'roepnaam',
        'geslacht_id', 'geboortedatum', 'land_id', 'nationaliteit_id',
        'BSN', 'adres', 'postcode', 'plaats', 'email', 'mobiel',
        'telefoon', 'geen_post', 'geen_email', 'overleden',
    );

    public $validate = array(
        'achternaam' => array(
            'notempty' => array(
                'rule' => array(
                    'notEmpty',
                ),
                'message' => 'Voer een achternaam in',
                //'allowEmpty' => false,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create'	// Limit validation to 'create' or 'update' operations
            ),
        ),
        'medewerker_id' => array(
            'notempty' => array(
                'rule' => array(
                    'notEmpty',
                ),
                'message' => 'Kies een medewerker',
                //'allowEmpty' => false,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create'	// Limit validation to 'create' or 'update' operations
            ),
        ),
        'land_id' => array(
            'notempty' => array(
                'rule' => array(
                    'notEmpty',
                ),
                'message' => 'Kies een land',
                //'allowEmpty' => false,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create'	// Limit validation to 'create' or 'update' operations
            ),
        ),
        'nationaliteit_id' => array(
            'notempty' => array(
                'rule' => array(
                    'notEmpty',
                ),
                'message' => 'Kies een nationaliteit',
                //'allowEmpty' => false,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create'	// Limit validation to 'create' or 'update' operations
            ),
        ),
        'email' => array(
            'email' => array(
                'rule' => array(
                    'email',
                ),
                'message' => 'Een geldig E-Mail adres invoeren',
                'allowEmpty' => true,
                'required' => false,
                //'last' => false, // Stop validation after this rule
            ),
        ),
    );

    public $belongsTo = array(
            'Geslacht' => array(
                    'className' => 'Geslacht',
                    'foreignKey' => 'geslacht_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => 'Geslacht.id desc',
            ),
            'Geboorteland' => array(
                    'className' => 'Land',
                    'foreignKey' => 'land_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'Nationaliteit' => array(
                    'className' => 'Nationaliteit',
                    'foreignKey' => 'nationaliteit_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'Medewerker' => array(
                    'className' => 'Medewerker',
                    'foreignKey' => 'medewerker_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'LasteIntake' => array(
                    'className' => 'Intake',
                    'foreignKey' => 'laste_intake_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'LaatsteRegistratie' => array(
                    'className' => 'Registratie',
                    'foreignKey' => 'laatste_registratie_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
    );

    public $hasMany = array(
            'Intake' => array(
                    'className' => 'Intake',
                    'foreignKey' => 'klant_id',
                    'dependent' => false,
                    'conditions' => '',
                    'fields' => '',
                    'order' => array(
                            'Intake.datum_intake DESC',
                            'Intake.modified DESC',
                    ),
            ),
            'AwbzIntake' => array(
                    'className' => 'AwbzIntake',
                    'foreignKey' => 'klant_id',
                    'dependent' => false,
                    'fields' => '',
                    'order' => array(
                            'AwbzIntake.datum_intake DESC',
                            'AwbzIntake.modified DESC',
                    ),
            ),
            'AwbzHoofdaannemer' => array(
                    'className' => 'AwbzHoofdaannemer',
                    'foreignKey' => 'klant_id',
                    'dependent' => false,
                    'order' => array(
                            'AwbzHoofdaannemer.begindatum DESC',
                    ),
            ),
            'AwbzIndicatie' => array(
                    'className' => 'AwbzIndicatie',
                    'foreignKey' => 'klant_id',
                    'dependent' => false,
                    'order' => array(
                            'AwbzIndicatie.begindatum DESC',
                    ),
            ),
            'Notitie' => array(
                    'className' => 'Notitie',
                    'foreignKey' => 'klant_id',
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
            'Registratie' => array(
                    'className' => 'Registratie',
                    'foreignKey' => 'klant_id',
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
            'Schorsing' => array(
                    'className' => 'Schorsing',
                    'foreignKey' => 'klant_id',
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
            'Opmerking' => array(
                    'className' => 'Opmerking',
                    'foreignKey' => 'klant_id',
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
            'Verslag' => array(
                    'className' => 'Verslag',
                    'foreignKey' => 'klant_id',
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
            'BotVerslag' => array(
                    'className' => 'BotVerslag',
                    'foreignKey' => 'klant_id',
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
            'Hi5Intake' => array(
                    'className' => 'Hi5Intake',
                    'foreignKey' => 'klant_id',
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
            'Hi5Evaluatie' => array(
                    'className' => 'Hi5Evaluatie',
                    'foreignKey' => 'klant_id',
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
            'Contactjournal' => array(
                    'className' => 'Contactjournal',
                    'foreignKey' => 'klant_id',
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
            'Verslaginfo' => array(
                    'className' => 'Verslaginfo',
                    'foreignKey' => 'klant_id',
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
            'Document' => array(
                    'className' => 'Attachment',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array(
                        'Document.model' => 'Klant',
                        'is_active' => 1,
                    ),
                    'dependent' => true,
                    'order' => 'created desc',
            ),
            'GroepsactiviteitenGroepenKlant' => array(
                    'className' => 'GroepsactiviteitenGroepenKlant',
                    'foreignKey' => 'klant_id',
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
            'GroepsactiviteitenKlant' => array(
                    'className' => 'GroepsactiviteitenKlant',
                    'foreignKey' => 'klant_id',
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
            'GroepsactiviteitenVerslag' => array(
                    'className' => 'GroepsactiviteitenVerslag',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array(
                        'GroepsactiviteitenVerslag.model' => 'Klant',
                    ),
                    'order' => 'GroepsactiviteitenVerslag.created DESC',
                    'dependent' => true,
            ),
            'GroepsactiviteitenDocument' => array(
                    'className' => 'Attachment',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array(
                        'GroepsactiviteitenDocument.model' => 'Klant',
                        'is_active' => 1,
                    ),
                    'dependent' => true,
                    'order' => 'created desc',
            ),
    );

    public $hasOne = array(
            'Traject' => array(
                    'className' => 'Traject',
                    'foreignKey' => 'klant_id',
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
            'BackOnTrack' => array(
                    'className' => 'BackOnTrack',
                    'foreignKey' => 'klant_id',
                    'dependent' => 'klant_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
                    'limit' => '',
                    'offset' => '',
                    'exclusive' => '',
                    'finderQuery' => '',
                    'counterQuery' => '',
            ),
            'GroepsactiviteitenIntake' => array(
                    'className' => 'GroepsactiviteitenIntake',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array(
                        'GroepsactiviteitenIntake.model' => 'Klant',
                    ),
                    'order' => '',
                    'dependent' => true,
            ),
            'IzDeelnemer' => array(
                    'className' => 'IzDeelnemer',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array(
                            'IzDeelnemer.model' => 'Klant',
                    ),
                    'order' => '',
                    'dependent' => true,
            ),
    );

    public $actsAs = array(
            'Containable',
    );

    public $contain = array(
            'Geslacht' => array(
                    'fields' => array(
                            'afkorting',
                            'volledig',
                    ),
            ),
            'Nationaliteit' => array(
                    'fields' => array(
                            'naam',
                            'afkorting',
                    ),
            ),
            'Geboorteland' => array(
                    'fields' => array(
                            'land',
                            'AFK2',
                            'AFK3',
                    ),
            ),
            'Medewerker' => array(
                    'fields' => array(
                            'tussenvoegsel',
                            'achternaam',
                            'voornaam',
                    ),
            ),
            'LasteIntake' => array(
                    'fields' => array(
                            'locatie1_id',
                            'locatie2_id',
                            'datum_intake',
                    ),
                    'Locatie1' => array(
                            'naam',
                    ),
                    'Locatie2' => array(
                            'naam',
                    ),
                    'Locatie3' => array(
                            'naam',
                    ),
            ),
            'BackOnTrack' => array(),
            'Intake' => array(),
            'Document' => array(),
    );

    public $deduplicationMethods = array(
            'no_birthdate' => 'Unknown date of birth',
            'birthdate' => 'Same date of birth',
            'surname' => 'Same surname',
            'relaxed_surname' => 'Surname like first name',
    );

    public function beforeValidate($options = array())
    {
        if (empty($this->data['Klant']['voornaam']) && empty($this->data['Klant']['roepnaam'])) {
            $this->invalidate('roepnaam', 'Voer de roep of voornaam in');

            return false;
        } else {
            return true;
        }
    }

    public function beforeSave($options = array())
    {
        if (!$this->id && empty($this->data['Klant']['id'])) {
            $this->send_admin_email = true;
            $this->changes = $this->data;
            if (isset($this->data['Klant'])) {
                $this->changes = array();
                foreach ($this->watchfields as $watch) {
                    if (isset($this->data['Klant'][$watch])) {
                        $this->changes[$watch] = $this->data['Klant'][$watch];
                    }
                }
            }
        } else {
            $current = $this->getById($this->data['Klant']['id']);
            $compare = $this->data;
            if (isset($this->data['Klant'])) {
                $compare = $this->data['Klant'];
            }
            $this->send_admin_email = false;
            $this->changes = array();
            foreach ($this->watchfields as $watch) {
                if (!isset($current[$watch]) || !isset($compare[$watch])) {
                    continue;
                }
                if ($current[$watch] != $compare[$watch]) {
                    $this->send_admin_email = true;
                    $this->changes[$watch] = $compare[$watch];
                }
            }
        }

        return parent::beforeSave($options);
    }

    public function completeVirtualFields($klanten)
    {
        $tbc_valid = Configure::read('TBC_months_period') * 30 * DAY;

        foreach ($klanten as $key => $k) {
            $datum_intake = strtotime($k['LasteIntake']['datum_intake']);
            $klanten[$key]['Klant']['new_intake_needed'] = 180 - (time() - $datum_intake) / DAY;

            $lastTBC = strtotime($k['Klant']['laatste_TBC_controle']);
            $needed = 'Ja';

            if ((time() - $lastTBC) < $tbc_valid) {
                $needed = 'Nee';
            }

            $klanten[$key]['Klant']['new_TBC_check_needed'] = $needed;
        }

        return $klanten;
    }

    public function set_registration_virtual_fields()
    {
        $tbc_valid = Configure::read('TBC_months_period') * 30 * DAY;

        $this->virtualFields['new_intake_needed'] = '(180 - (DATEDIFF(CURDATE(),`LasteIntake`.`datum_intake`)))';

        $this->virtualFields['new_TBC_check_needed'] = 'IF(DATEDIFF(CURDATE(),`Klant`.`laatste_TBC_controle`) < '.$tbc_valid.', "Nee", "Ja")';
    }

    public function update_last_intakes()
    {
        $ids = $this->find('list', array(
                'fields' => 'id',
        ));

        foreach ($ids as $id) {
            $this->read(null, $id);
            if (isset($this->data['Intake'][0])) {
                $last_intake = $this->data['Intake'][0];
                $this->saveField('laste_intake_id', $last_intake['id']);
                debug("klant $id updated with ".$last_intake['id'].' ('.$last_intake['datum_intake'].')');
            } else {
                debug("klant $id has no intake");
            }
        }
    }

    public function update_last_intake($klantId)
    {
        $lastIntakeInfo = $this->Intake->find('first', array(
            'order' => 'Intake.datum_intake DESC',
            'conditions' => array(
                'Intake.klant_id' => $klantId,
            ),
            'fields' => array('id', 'datum_intake'),
        ));
        if ($lastIntakeInfo) {
            $this->saveField('laste_intake_id', $lastIntakeInfo['Intake']['id']);
        }
    }

    public function TimeDiffShort($time, $period)
    {
        $startTime = strtotime($time);

        if ($startTime < 0) {
            $days = -100000;
        } else {
            $endTime = strtotime($period, $startTime);
            $difference = $endTime - time();
            $days = floor($difference / 60 / 60 / 24);
        }
        $return = '';

        if ($days < -10000) {
            $return = 'onbekend';
        } elseif ($days < 0) {
            $return = 'is verlopen';
        } elseif ($days < 14) {
            $return = 'over '.$days.' dagen';
        } elseif ($days < 28) {
            $weeks = round($days / 7);
            $return = 'over '.$weeks.' weken';
        } else {
            $months = round($days / 30);
            $return = 'over '.$months.' maanden';
        }

        if ($days <= 0) {
            $return = '<span class="warning">'.$return.'</span>';
        } elseif ($days <= 14) {
            $return = '<span class="alert">'.$return.'</span>';
        }

        return $return;
    }

    public function OFF_paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array())
    {
        if (!is_array($order) || empty($order)) {
            $order = array(
                    'Klant.achternaam' => 'asc',
                    'Klant.voornaam' => 'asc',
            );
        }
        debug($this->paginator);
        $contain = $this->contain;

        return $this->find('all', compact('conditions', 'contain', 'order', 'limit', 'page', 'group'));
    }

    public function beforeFind(&$queryData)
    {
        if (!$this->showDisabled) {
            $queryData['conditions'][$this->alias.'.disabled'] = false;
        }

        return true;
    }

    public function set_last_registration($klant_id, $registratie_id = null)
    {
        $this->recursive = -1;
        $klant_fields = array(
                'id',
                'laatste_registratie_id',
                'roepnaam',
                'achternaam',
                'land_id',
                'nationaliteit_id',
        );
        if (!$this->read($klant_fields, $klant_id)) {
            return false;
        }

        if (empty($registratie_id)) {
            $this->Registratie->recursive = -1;
            $recent_registration = &$this->Registratie->find('all',
                array(
                        'conditions' => array(
                                'Registratie.klant_id' => $klant_id,
                        ),
                        'order' => 'Registratie.modified DESC',
                        'limit' => 1,
                        'fields' => 'Registratie.id',
                ));
            if (!empty($recent_registration)) {
                $registratie_id = $recent_registration[0]['Registratie']['id'];
            }
        }

        if ($registratie_id == $this->data['Klant']['laatste_registratie_id']) {
            return true;
        }

        $this->set('laatste_registratie_id', $registratie_id);
        if (!$this->save($this->data, false)) { //didn't save
            return false;
        }

        return true;
    }

    public function setHi5Info($klantId)
    {
        $klant = $this->find('first',
            array(
                'conditions' => array(
                    'Klant.id' => $klantId,
                ),
                'contain' => array(
                    'Geboorteland',
                    'Nationaliteit',
                    'Geslacht',
                    'Medewerker',
                    'Traject' => array(
                        'Trajectbegeleider' => array(
                            'fields' => 'name',
                        ),
                        'Werkbegeleider' => array(
                            'fields' => 'name',
                        ),
                    ),
                    'Hi5Intake' => array(
                        'Medewerker' => array(
                            'fields' => array(
                                'name',
                            ),
                        ),
                        'fields' => array(
                            'id',
                            'created',
                            'modified',
                            'datum_intake',
                            'medewerker_id',
                        ),
                        'order' => array(
                            'datum_intake DESC',
                        ),
                    ),
                    'Hi5Evaluatie' => array(
                        'Medewerker' => array(
                            'fields' => array(
                                'name',
                            ),
                        ),
                        'fields' => array(
                            'id',
                            'created',
                            'datumevaluatie',
                            'modified',
                            'medewerker_id',
                        ),
                        'order' => array(
                            'datumevaluatie DESC',
                        ),
                    ),
                    'Document' => array(
                        'conditions' => array(
                            'Document.is_active' => 1,
                            'Document.group' => Attachment::GROUP_HI5,
                        ),
                    ),
                ),
            ));
        $this->set($klant);
    }

    public function getLastIntakeAddress($klantId)
    {
        $lastIntakeInfo = $this->Intake->find('first', array(
            'order' => 'Intake.datum_intake DESC',
            'conditions' => array(
                'Intake.klant_id' => $klantId,
            ),
        ));
        $lastIntake = $lastIntakeInfo['Intake'];
        $lastIntakeDate = empty($lastIntake['postadres']) ? '0000-00-00' : $lastIntake['created'];
        $lastIntakeInfo = $this->AwbzIntake->find('first',
            array(
                    'order' => 'AwbzIntake.created DESC',
                    'conditions' => array(
                            'AwbzIntake.created >' => $lastIntakeDate,
                            'AwbzIntake.klant_id' => $klantId,
                    ),
            ));
        if (!empty($lastIntakeInfo)) {
            $lastIntake = $lastIntakeInfo['AwbzIntake'];
            $lastIntakeDate = $lastIntake['created'];
        }

        $lastIntakeInfo = $this->Hi5Intake->find('first',
            array(
                    'order' => 'Hi5Intake.created DESC',
                    'conditions' => array(
                            'Hi5Intake.created >' => $lastIntakeDate,
                            'Hi5Intake.klant_id' => $klantId,
                    ),
            ));
        if (!empty($lastIntakeInfo)) {
            $lastIntake = $lastIntakeInfo['Hi5Intake'];
            $lastIntakeDate = $lastIntake['created'];
        }

        return array(
                'postadres' => $lastIntake['postadres'],
                'postcode' => $lastIntake['postcode'],
                'woonplaats' => $lastIntake['woonplaats'],
                'verblijf_in_NL_sinds' => $lastIntake['verblijf_in_NL_sinds'],
                'verblijf_in_amsterdam_sinds' => $lastIntake['verblijf_in_amsterdam_sinds'],
                'verblijfstatus_id' => $lastIntake['verblijfstatus_id'],
                'legitimatie_id' => $lastIntake['legitimatie_id'],
                'legitimatie_nummer' => $lastIntake['legitimatie_nummer'],
                'legitimatie_geldig_tot' => $lastIntake['legitimatie_geldig_tot'],
        );
    }

    public function goesToInfobalie($klant)
    {
        return
            $klant['Klant']['doorverwijzen_naar_amoc'] ||
            in_array($klant['Klant']['land_id'], Configure::read('Landen.AMOC'));
    }

    public function findDuplicates($data, $recursive = 0)
    {
        $this->recursive = $recursive;

        $surname = $data[$this->alias]['achternaam'];
        $date = $data[$this->alias]['geboortedatum'];
        if (is_array($date)) {
            $birth = $date['year'].'-'.$date['month'].'-'.$date['day'];
        } else {
            $birth = $date;
        }

        $conditions = array(
            'disabled' => 0,
            'OR' => array(
                array(
                    'Klant.achternaam' => $surname,
                    'Klant.achternaam !=' => '',
                ),
                array(
                    'Klant.voornaam' => $surname,
                    'Klant.voornaam !=' => '',
                ),
                array(
                    'Klant.roepnaam' => $surname,
                    'Klant.roepnaam !=' => '',
                ),
                'geboortedatum' => $birth,
            ),
        );

        if (isset($data[$this->alias]['id'])) {
            $conditions[$this->alias.'.id >'] = $data[$this->alias]['id'];
        }

        App::import('Sanitize');
        $surnameQuoted = "'".Sanitize::escape($surname)."'";
        $hits = $this->find('all', array(
            'conditions' => $conditions,
            'order' => array(
                "10 * (Klant.achternaam = $surnameQuoted and Klant.achternaam != '') +
				 3 * (Klant.roepnaam = $surnameQuoted and Klant.roepnaam != '' and Klant.roepnaam != Klant.achternaam) +
				 1 * (Klant.voornaam = $surnameQuoted and Klant.voornaam != '') +
				 5 * (Klant.geboortedatum = '$birth') desc",
                'Klant.id',
            ),
        ));

        return $hits;
    }

    public function disable($id)
    {
        $this->recursive = -1;
        if (!$this->read(null, $id)) {
            return false;
        }
        $this->set('disabled', 1);

        return $this->save();
    }

    public function enable($id)
    {
        $this->recursive = -1;
        $this->showDisabled = true;
        if (!$this->read(null, $id)) {
            return false;
        }
        $this->set('disabled', 0);

        return $this->save();
    }

    public function findAllDuplicates($mode = 'birthdate')
    {
        $this->recursive = -1;

        if (!isset($this->deduplicationMethods[$mode])) {
            throw new Exception('Undefined mode '.$mode);
        }

        switch ($mode) {
            case 'no_birthdate':
                $pairs = $this->query("
						select k1.id id1, k2.id id2, concat_ws(' ', k1.voornaam, k1.roepnaam, k1.achternaam) name1, concat_ws(' ', k2.voornaam, k2.roepnaam, k2.achternaam) name2, k1.geboortedatum
						from klanten k1
						join klanten k2
						on (k1.geboortedatum = k2.geboortedatum and k1.id > k2.id)
						where k1.disabled = 0
						and k2.disabled = 0
						and (k1.geboortedatum IS NULL or k1.geboortedatum = '0000-00-00')

						order by k1.achternaam
						");
                $matched_key = 'k1.geboortedatum';
                break;

            case 'birthdate':
                $pairs = $this->query("
							select k1.id id1, k2.id id2, DATE_FORMAT(k1.geboortedatum, '%e %b %Y') formatted_date, concat_ws(' ', k1.voornaam, k1.roepnaam, k1.achternaam) name1, concat_ws(' ', k2.voornaam, k2.roepnaam, k2.achternaam) name2, k1.geboortedatum
						from klanten k1
						join klanten k2
						on (k1.geboortedatum = k2.geboortedatum and k1.id > k2.id)
						where k1.disabled = 0
						and k2.disabled = 0
						and k1.geboortedatum IS NOT NULL AND k1.geboortedatum != '0000-00-00'
						order by k1.geboortedatum
						");
                $matched_key = '0.formatted_date';
                break;

            case 'surname':
                $pairs = $this->query("
						select k1.id id1, k2.id id2, k1.achternaam, concat_ws(' ', k1.voornaam, k1.roepnaam, k1.achternaam) name1, concat_ws(' ', k2.voornaam, k2.roepnaam, k2.achternaam) name2, k1.geboortedatum
						from klanten k1
						join klanten k2
						on (k1.id > k2.id and trim(k1.achternaam) = trim(k2.achternaam))
						where k1.disabled = 0
						and k2.disabled = 0
						order by k1.achternaam
						");
                $matched_key = 'k1.achternaam';
                break;

            case 'relaxed_surname':
                $pairs = $this->query("
						select k1.id id1, k2.id id2, k2.achternaam, concat_ws(' ', k1.voornaam, k1.roepnaam, k1.achternaam) name1, concat_ws(' ', k2.voornaam, k2.roepnaam, k2.achternaam) name2, k1.geboortedatum
						from klanten k1
						join klanten k2
						on ( 
							k2.id != k1.id AND
							k2.achternaam != '' AND
							(trim(k1.voornaam) = trim(k2.achternaam) )
							or 
							(trim(k1.roepnaam) = trim(k2.achternaam) )
							)
						where k1.disabled = 0
						and k2.disabled = 0
						AND k1.id != k2.id
						order by k2.achternaam
						");
                $matched_key = 'k2.achternaam';
                break;

            case 'less_relaxed_surname':
                $pairs = $this->query("
						select k1.id id1, k2.id id2, k2.achternaam, concat_ws(' ', k1.voornaam, k1.roepnaam, k1.achternaam) name1, concat_ws(' ', k2.voornaam, k2.roepnaam, k2.achternaam) name2, k1.geboortedatum
						from klanten k1
						join klanten k2
						on (
							(trim(k1.voornaam) = trim(k2.achternaam) and ( SUBSTR(k1.achternaam, 1, 1) = SUBSTR(k2.voornaam, 1, 1)) OR SUBSTR(k1.achternaam, 1, 1) = SUBSTR(k2.roepnaam, 1, 1)) )
							or 
							(trim(k1.roepnaam) = trim(k2.achternaam) and ( SUBSTR(k1.achternaam, 1, 1) = SUBSTR(k2.roepnaam, 1, 1) OR  SUBSTR(k1.achternaam, 1, 1) = SUBSTR(k2.voornaam, 1, 1) )
							)
						where k1.disabled = 0
						and k2.disabled = 0
						order by name1
						");
                $matched_key = 'k2.achternaam';
                break;

            default:
                throw new Exception('Undefined mode '.$mode);

        }

        $this->sets = array();
        $this->setIndexes = array();

        foreach ($pairs as $p) {
            $id1 = $p['k1']['id1'];
            $id2 = $p['k2']['id2'];
            $match = Set::classicExtract($p, $matched_key);
            $name1 = $p[0]['name1'];
            $name2 = $p[0]['name2'];

            $this->addPair($id1, $id2, $match, $name1, $name2);
        }

        return $this->sets;
    }

    private function addPair($id1, $id2, $match, $name1, $name2)
    {
        if (!empty($this->setIndexes[$id2]) &&
                !empty($this->setIndexes[$id1])) {
            return;
        } elseif (!empty($this->setIndexes[$id1])) {
            $index = $this->setIndexes[$id1];
            $this->sets[$index]['klanten'][] = array($id2, $name2);
            $this->setIndexes[$id2] = $index;
        } elseif (!empty($this->setIndexes[$id2])) {
            $index = $this->setIndexes[$id2];
            $this->sets[$index]['klanten'][] = array($id1, $name1);
            $this->setIndexes[$id1] = $index;
        } else {
            $index = count($this->sets) + 1;
            $this->sets[$index] = array(
                'match' => $match,
                'klanten' => array(
                    array($id1, $name1),
                    array($id2, $name2),
                ),
            );

            $this->setIndexes[$id1] = $index;
            $this->setIndexes[$id2] = $index;
        }
    }

    /**
     * Move all associated models from the old klant ids to the new one.
     * Returns an array of moved objects (AssociationName => count).
     *
     * @param int   $newId  New klant id
     * @param array $oldIds List of old ids
     *
     * @return array
     */
    public function moveAssociations($newId, $oldIds)
    {
        $associations = array_merge($this->hasOne, $this->hasMany);
        $result = array();
        foreach ($associations as $aName => $assoc) {
            $fields = array($assoc['foreignKey'] => $newId);
            $conditions = array($assoc['foreignKey'] => $oldIds);

            $this->$aName->recursive = -1;

            $this->$aName->updateAll($fields, $conditions);
            $result[$aName] = $this->$aName->getAffectedRows();
        }
        $this->set_last_registration($newId);
        $this->update_last_intake($newId);

        return $result;
    }

    public function disableMultiple($ids, $merged_id)
    {
        $this->recursive = -1;

        return $this->updateAll(
            array('Klant.disabled' => 1, 'Klant.merged_id' => $merged_id,
            'Klant.modified' => "'".date('Y-m-d H:i:s')."'", ),
            array('Klant.id' => $ids)
        );
    }
    public function get_selectie($data, $only_email = false)
    {
        $conditions = array();

        if (!empty($data['Groepsactiviteit']['werkgebieden'])) {
            $conditions['Klant.werkgebied'] = $data['Groepsactiviteit']['werkgebieden'];
        }

        if (!empty($only_email)) {
            $conditions['email NOT'] = null;
            $conditions['email NOT'] = '';
        }

        $join_conditions = array(
                'Klant.id = GroepsactiviteitenGroepenKlant.klant_id',
        );

        $join_table = Inflector::pluralize(Inflector::underscore('GroepsactiviteitenGroepenKlant'));

        if (!empty($data['Groepsactiviteit']['activiteitengroepen'])) {
            $join_conditions['GroepsactiviteitenGroepenKlant.groepsactiviteiten_groep_id'] = $data['Groepsactiviteit']['activiteitengroepen'];
        }

        if (!empty($data['Groepsactiviteit']['communicatie_type'])) {
            $or = array();
            if (in_array('communicatie_email', $data['Groepsactiviteit']['communicatie_type'])) {
                $or['GroepsactiviteitenGroepenKlant.communicatie_email'] = 1;
            }
            if (in_array('communicatie_post', $data['Groepsactiviteit']['communicatie_type'])) {
                $or['GroepsactiviteitenGroepenKlant.communicatie_post'] = 1;
            }
            if (in_array('communicatie_telefoon', $data['Groepsactiviteit']['communicatie_type'])) {
                $or['GroepsactiviteitenGroepenKlant.communicatie_telefoon'] = 1;
            }
            $join_conditions['OR'] = $or;
        }

        $join_conditions['OR'] = array(
                'GroepsactiviteitenGroepenKlant.einddatum' => null,
                'GroepsactiviteitenGroepenKlant.einddatum >=' => date('Y-m-d'),
        );

        $contain = array('GroepsactiviteitenIntake');

        $joins = array();
        $joins[] = array(
            'table' => $join_table,
            'alias' => 'GroepsactiviteitenGroepenKlant',
            'type' => 'INNER',
            'conditions' => $join_conditions,

        );

        $options = array(
            'conditions' => $conditions,
            'joins' => $joins,
            'contain' => $contain,
            'group' => array('Klant.id'),
            'fields' => array('id', 'voornaam', 'tussenvoegsel', 'achternaam', 'roepnaam', 'geslacht_id',
                'geboortedatum', 'land_id', 'nationaliteit_id', 'BSN', 'medewerker_id', 'adres',
                'postcode', 'werkgebied', 'postcodegebied', 'plaats', 'email', 'mobiel', 'telefoon',
                'opmerking', 'geen_post', 'geen_email', 'disabled', 'created', 'modified', 'geen_email', 'name',
                'name1st_part', 'name2nd_part', 'klant_nummer',
                'GroepsactiviteitenIntake.id',
                'GroepsactiviteitenIntake.model',
                'GroepsactiviteitenIntake.foreign_key',
                'GroepsactiviteitenIntake.groepsactiviteiten_afsluiting_id',
                'GroepsactiviteitenIntake.medewerker_id',
                'GroepsactiviteitenIntake.gespreksverslag',
                'GroepsactiviteitenIntake.ondernemen',
                'GroepsactiviteitenIntake.overdag',
                'GroepsactiviteitenIntake.ontmoeten',
                'GroepsactiviteitenIntake.regelzaken',
                'GroepsactiviteitenIntake.informele_zorg',
                'GroepsactiviteitenIntake.dagbesteding',
                'GroepsactiviteitenIntake.inloophuis',
                'GroepsactiviteitenIntake.hulpverlening',
                'GroepsactiviteitenIntake.gezin_met_kinderen',
                'GroepsactiviteitenIntake.intakedatum',
                'GroepsactiviteitenIntake.afsluitdatum',
                'count(*) as count',
                'min(GroepsactiviteitenGroepenKlant.startdatum) as startdatum',
            ),
        );

        $personen = $this->find('all', $options);

        return $personen;
    }
    public function diensten($id)
    {
        if (is_array($id)) {
            $id = $id['Klant']['id'];
        }
        $klant = $this->getAllById($id);
        if (empty($klant)) {
            return array();
        }
        $diensten = array();
        if (!empty($klant['GroepsactiviteitenIntake'])) {
            $diensten[] = array(
                'name' => 'GA',
                'url' => array('controller' => 'groepsactiviteiten', 'action' => 'intakes', 'Klant', $klant['Klant']['id']),
                'from' => $klant['GroepsactiviteitenIntake']['intakedatum'],
                'to' => null,
                'type' => 'date',
                'value' => '',
            );
        }
        if (!empty($klant['IzDeelnemer'])) {
            $diensten[] = array(
                'name' => 'Iz',
                'url' => array('controller' => 'iz_deelnemers', 'action' => 'toon_aanmelding', 'Klant', $klant['Klant']['id'], $klant['IzDeelnemer']['id']),
                'from' => $klant['IzDeelnemer']['datum_aanmelding'],
                'to' => $klant['IzDeelnemer']['datumafsluiting'],
                'type' => 'date',
                'value' => '',
            );
        }
        if (!empty($klant['Verslag'][0])) {
            $diensten[] = array(
                'name' => 'Mw',
                'url' => array('controller' => 'maatschappelijk_werk', 'action' => 'view', $klant['Klant']['id']),
                'from' => $klant['Verslag'][0]['datum'],
                'to' => null,
                'type' => 'date',
                'value' => '',
            );
        }
        if (!empty($klant['Hi5Intake'][0])) {
            $diensten[] = array(
                'name' => 'Hi5',
                'url' => array('controller' => 'hi5', 'action' => 'view', $klant['Klant']['id']),
                'from' => $klant['Hi5Intake'][0]['datum_intake'],
                'to' => null,
                'type' => 'date',
                'value' => '',
            );
        }
        $all = $this->Intake->Locatie1->locaties();
        $locaties = array_unique(Set::ClassicExtract($klant['Registratie'], '{n}.locatie_id'));
        $value = '';
        foreach ($locaties as $locatie_id) {
            if (!empty($value)) {
                $value .= ',';
            }
            if (isset($all[$locatie_id])) {
                $value .= $all[$locatie_id];
            } else {
                $value .= $locatie_id;
            }
        }
        if (!empty($klant['Hi5Intake'][0])) {
            $diensten[] = array(
                'name' => 'Locaties',
                'url' => null,
                'from' => null,
                'to' => null,
                'type' => 'string',
                'value' => $value,
            );
        }
        if (!empty($klant['LasteIntake']['locatie1_id'])) {
            $diensten[] = array(
                'name' => 'Gebr. ruimte',
                'url' => null,
                'from' => null,
                'to' => null,
                'type' => 'string',
                'value' => $all[$klant['LasteIntake']['locatie1_id']],
            );
        }

        return $diensten;
    }
}
