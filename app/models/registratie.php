<?php

class Registratie extends AppModel
{
    public $name = 'Registratie';

    public $validate = [
        'locatie_id' => [
            'notempty' => [
                'rule' => 'notEmpty',
            ],
        ],
        'klant_id' => [
            'notempty' => [
                'rule' => 'notEmpty',
            ],
        ],
    ];

    public $belongsTo = [
        'Locatie' => [
            'className' => 'Locatie',
            'foreignKey' => 'locatie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Klant' => [
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];

    public function getRegistratiesConditions($locatie_id = null, $type = 'active')
    {
        $this->Behaviors->attach('Containable');

        $and = ['locatie_id' => $locatie_id];
        if ('active' == $type) {
            $and['buiten'] = null;
        } elseif ('today_inactive' == $type) {
            $this->Locatie->id = $locatie_id;
            $nachtopvang = $this->Locatie->field('nachtopvang');
            $timelimit = $this->get_timelimit($locatie_id);
            $and['binnen >'] = $timelimit;
        }
        $registraties = ['conditions' => ['AND' => $and],
            'contain' => ['Klant' => ['Intake' => [
                'fields' => ['mag_gebruiken', 'locatie1_id', 'locatie2_id',
                'locatie3_id', ],
            ]]],
        ];

        return $registraties;
    }

    public function getActiveRegistraties(&$regular_klanten, &$gebruikers, $locatie_id, $sort = null)
    {
        $this->Locatie->id = $locatie_id;
        $locatie = $this->Locatie->field('naam');
        $gebruikersruimte = $this->Locatie->field('gebruikersruimte');
        $nachtopvang = $this->Locatie->field('nachtopvang');
        $this->Behaviors->attach('Containable');
        $contain = [
            'Klant' => [
                'fields' => ['voornaam', 'achternaam', 'roepnaam', 'laatste_TBC_controle'],
            ],
        ];

        $joins = [
            ['table' => 'klanten',
                'alias' => 'Klant',
                'type' => 'LEFT',
                'conditions' => [
                    'Klant.id = Registratie.klant_id',
                ],
            ],
        ];

        if ($gebruikersruimte) {
            array_push($joins,
                ['table' => 'intakes',
                    'alias' => 'LasteIntake',
                    'type' => 'LEFT',
                    'conditions' => [
                        'LasteIntake.id = Klant.laste_intake_id',
                    ],
                ]
            );

            $fields = ['LasteIntake.mag_gebruiken'];
            $order = ['LasteIntake.mag_gebruiken ASC, Registratie.created DESC'];
        } else {
            $fields = [];
            array_push($contain, 'Klant');
            $order = null;
            $intake_field = null;
        }

        $registratie_fields = [
            'id',
            'locatie_id',
            'klant_id',
            'binnen',
            'buiten',
            'douche',
            'mw',
            'kleding',
            'maaltijd',
            'veegploeg',
            'activering',
            'gbrv',
        ];
        $klant_fields = ['achternaam', 'voornaam', 'roepnaam', 'laatste_TBC_controle'];

        foreach ($registratie_fields as $i => $rf) {
            $registratie_fields[$i] = 'Registratie.'.$rf;
        }

        foreach ($klant_fields as $i => $kf) {
            $klant_fields[$i] = 'Klant.'.$kf;
        }
        $fields = array_merge($fields, array_merge($registratie_fields, $klant_fields));

        $this->recursive = -1;

        $options = [
            'fields' => $fields,
            'conditions' => [
                'locatie_id' => $locatie_id,
                'Registratie.closed' => false,
            ],
            'joins' => $joins,
            'order' => $order,
        ];

        $regular_klanten = $this->find('all', $options);

        if ($gebruikersruimte) {
            $gebruikers = [];
            $i = count($regular_klanten) - 1;
            $gebruiker = true;

            while ($i > 0) {
                if (!$regular_klanten[$i]['LasteIntake']['mag_gebruiken']) {
                    break;
                }

                array_unshift($gebruikers, array_pop($regular_klanten));
                --$i;
            }
        }
    }

    public function getRecentlyUnregistered($locatie_id, $previous_days = 0, $active_registr = [], $gebruiker_registr = [])
    {
        $timelimit = $this->get_timelimit($locatie_id, $previous_days);

        $exception_list = [];
        foreach ($active_registr as $registered_client) {
            $exception_list[] = (int) $registered_client['Registratie']['klant_id'];
        }
        foreach ($gebruiker_registr as $registered_client) {
            $exception_list[] = (int) $registered_client['Registratie']['klant_id'];
        }
        $exception_list_query = '';
        if (!empty($exception_list)) {
            $exception_list = implode(',', array_unique($exception_list));
            $exception_list_query = 'AND klant_id NOT IN ('.$exception_list.')';
        }

        $result = $this->query('
            SELECT Registratie.id, Registratie.binnen, Registratie.buiten, Registratie.douche, Registratie.mw, Registratie.kleding, Registratie.maaltijd, Registratie.veegploeg, Registratie.activering, Registratie.gbrv,
                Klant.voornaam, Klant.achternaam, Klant.roepnaam, Klant.tussenvoegsel, Klant.laatste_TBC_controle, Registratie.klant_id
            FROM registraties Registratie
            INNER JOIN (
                SELECT klant_id, MAX(buiten) AS max_buiten
                FROM registraties
                WHERE closed = 1
                AND locatie_id = '.(int) $locatie_id.'
                AND binnen > "'.$timelimit.'"
                '.$exception_list_query.'
                GROUP BY klant_id
            ) AS laatste_registratie ON laatste_registratie.klant_id = Registratie.klant_id AND laatste_registratie.max_buiten = Registratie.buiten
            INNER JOIN klanten Klant ON Registratie.klant_id = Klant.id
        ');

        return $result;
    }

    public function automaticCheckOut($conditions, $now = null)
    {
        $this->LocatieTijd = ClassRegistry::init('LocatieTijd');
        $conditions = [
            'OR' => [
                'AND' => $conditions + ['closed' => 0],
                'buiten < binnen',
            ],
        ];

        if (!$now) {
            $now = time();
        } else {
            $now = strtotime($now);
        }
        $count = $this->find('count', [
            'recursive' => 1,
            'conditions' => $conditions,
        ]);
        $this->log(
            sprintf('Inspecting %d registrations with autocheckout.', $count),
            'auto_checkout');

        $pending = $this->find('all', [
            'recursive' => 1,
            'conditions' => $conditions,
            'fields' => ['Registratie.*', 'Locatie.nachtopvang'],
            'limit' => 10000,
        ]);
        $cnt = 0;
        $saved = [];

        foreach ($pending as $registratie) {
            $locationId = $registratie['Registratie']['locatie_id'];
            $in = strtotime($registratie['Registratie']['binnen']);
            $closeOnInDay = $this->LocatieTijd->getClosingTime($locationId, $in);

            if ($now < $closeOnInDay) {
                continue;
            }

            $out = $this->calculateAutoCheckoutTime(
                $locationId,
                $in,
                $closeOnInDay,
                $now,
                $registratie['Registratie']['id']
            );

            if (!$out) {
                continue;
            }

            $this->removeKlantFromAllQueueLists($registratie);

            $registratie['Registratie']['buiten'] = $out;

            if ($this->save($registratie['Registratie'])) {
                ++$cnt;
                $saved[$registratie['Registratie']['id']] = [$in, $out];
                $this->log(
                    $registratie['Registratie']['id'].', '.
                    $registratie['Registratie']['klant_id'].', '.
                    date('Y-m-d H:i:s', $in).', '.
                    $out,
                    'auto_checkout');
            }
        }

        return $cnt;
    }

    private function calculateAutoCheckoutTime($locationId, $in, $closeOnInDay, $now, $registratieId)
    {
        if ($in < $closeOnInDay) {
            $out = date('Y-m-d H:i:s', $closeOnInDay);
        } else {
            $closeOnNextDay = $this->LocatieTijd->getClosingTime($locationId, $in + DAY);
            if ($closeOnNextDay) {
                if ($closeOnNextDay > $now) {
                    return null;
                }

                if ($closeOnNextDay > $in + DAY) {
                    $out = date('Y-m-d H:i:s', $closeOnNextDay - DAY);
                } else {
                    $out = date('Y-m-d H:i:s', $closeOnNextDay);
                }
            } else {
                $out = date('Y-m-d', $in + DAY).' '.date('H:i:s', $closeOnInDay);

                if (strtotime($out) > $in + DAY) {
                    $out = date('Y-m-d', $in).' '.date('H:i:s', $closeOnInDay);
                }

                if (strtotime($out) < $in) {
                    $out = date('Y-m-d', $in + DAY).' '.date('H:i:s', $closeOnInDay);
                }

                $this->log('Error: location is not open on next day. '.
                    'Registration id:'.$registratieId,
                    'auto_checkout'
                );
            }
        }

        return $out;
    }

    public function countActiveRegistraties(&$registraties)
    {
        if (!is_null($registraties) && is_numeric($registraties)) {
            return $this->find('count', [
                'conditions' => [
                    'Registratie.locatie_id' => $registraties,
                    'Registratie.closed' => false,
                ],
            ]);
        } elseif (is_array($registraties)) {
            $count = 0;
            foreach ($registraties as $r) {
                if (is_array($r) && array_key_exists('Registratie', $r)
                    && array_key_exists('buiten', $r['Registratie'])
                    && empty($r['Registratie']['buiten'])
                ) {
                    ++$count;
                }
            }

            return $count;
        } else {
            return 0;
        }
    }

    public function get_timelimit($locationId, $previous_days = 0)
    {
        $locationId = (int) $locationId;
        $previous_days = (int) $previous_days;

        $this->LocatieTijd = ClassRegistry::init('LocatieTijd');

        $result = date('Y-m-d H:i:s', $this->LocatieTijd->getLastClosingTime(
            $locationId,
            strtotime("-$previous_days days - 3 hours")
        ));

        return $result;
    }

    public function registratieCheckOut($registratie_id = null, $now = null)
    {
        if (!$now) {
            $now = date('Y-m-d H:i:s', time());
        }

        if ($registratie_id) {
            if ($registratie = $this->findById($registratie_id)) {
                if (null == $registratie['Registratie']['buiten']) {
                    $this->removeKlantFromAllQueueLists($registratie);
                    $registratie['Registratie']['buiten'] = $now;

                    if ($this->save($registratie['Registratie'])) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function checkoutKlantFromAllLocations($klantId)
    {
        $ids = $this->find('list', [
            'recursive' => -1,
            'conditions' => [
                'klant_id' => $klantId,
                'buiten' => null,
            ],
            'fields' => ['id'],
        ]);

        $result = 0;
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $this->registratieCheckOut($id);
                ++$result;
            }
        }

        return $result;
    }

    public function removeKlantFromAllQueueLists(&$registratie)
    {
        if ($registratie['Registratie']['douche'] > 0
            || $registratie['Registratie']['gbrv'] > 0
            || $registratie['Registratie']['mw'] > 0
        ) {
            $registratie_data = [];
            $conditions = $this->getRegistratiesConditions($registratie['Registratie']['locatie_id']);
            $registraties = $this->find('all', $conditions);

            foreach ($registraties as $key => $registratiev) {
                if ($registratiev['Registratie']['id'] == $registratie['Registratie']['id']) {
                    $registratie_data['Registratie'] = $registratiev['Registratie'];
                }
            }

            if ($registratie['Registratie']['douche'] > 0) {
                $this->delKlantFromShowerList($registratie['Registratie']['id'],
                    $registraties, $registratie_data);
                $registratie['Registratie']['douche'] = 0;
            }

            if ($registratie['Registratie']['mw'] > 0) {
                $this->delKlantFromQueueList($registratie['Registratie']['id'], 'mw',
                    $registraties, $registratie_data);
                $registratie['Registratie']['mw'] = 0;
            }

            if ($registratie['Registratie']['gbrv'] > 0) {
                $this->delKlantFromQueueList($registratie['Registratie']['id'], 'gbrv',
                    $registraties, $registratie_data);
                $registratie['Registratie']['grbv'] = 0;
            }
        }
    }

    public function delKlantFromQueueList($registratie_id, $fieldname, &$registraties, &$registratie_data)
    {
        $registraties_list = [];

        foreach ($registraties as $key => $registratie) {
            if ($registratie['Registratie'][$fieldname] > 0) {
                $registraties_list[$registratie['Registratie']['id']] = $registratie['Registratie'][$fieldname];
            }
        }

        asort($registraties_list);
        $r_to_save = [];

        if ($registratie_data['Registratie'][$fieldname] > 0) {
            unset($registraties_list[$registratie_id]);
            $r_to_save[$registratie_data['Registratie']['id']]['id'] = $registratie_data['Registratie']['id'];
            $r_to_save[$registratie_data['Registratie']['id']][$fieldname] = -1;
            $inc = 1;

            foreach ($registraties_list as $key => $value) {
                $r_to_save[$key]['id'] = $key;
                $r_to_save[$key][$fieldname] = $inc;
                ++$inc;
            }
        } elseif (-1 == $registratie_data['Registratie'][$fieldname]) {
            $r_to_save[$registratie_data['Registratie']['id']]['id'] = $registratie_data['Registratie']['id'];
            $r_to_save[$registratie_data['Registratie']['id']][$fieldname] = 0;
        }

        $this->saveAll($r_to_save);

        foreach ($registraties as $r_key => $registratie_value) {
            foreach ($r_to_save as $registratie_saved) {
                if ($registratie_value['Registratie']['id'] == $registratie_saved['id']) {
                    $registraties[$r_key]['Registratie'][$fieldname] = $registratie_saved[$fieldname];
                }
            }
        }

        return $registraties;
    }

    public function updateQueueList($action, $fieldname, $registratie_id, $locatie_id)
    {
        $registraties = $this->find('all', $this->getRegistratiesConditions($locatie_id));

        $registratie_data = [];
        $updated_registratie_key = null;

        foreach ($registraties as $key => $registratie) {
            if ($registratie['Registratie']['id'] == $registratie_id) {
                $updated_registratie_key = $key;
                $registratie_data['Registratie'] = $registratie['Registratie'];
            }
        }

        if (empty($registratie_data)) {
            return $registraties;
        }

        switch ($action) {
            case 'add':
                $max_value = 0;
                $max_key = 0;
                if (null == $registratie_data['Registratie']['buiten']) {
                    foreach ($registraties as $key => $registratie) {
                        if ($registratie['Registratie'][$fieldname] > $max_value) {
                            $max_value = $registratie['Registratie'][$fieldname];
                            $max_key = $key;
                        }
                    }
                    if ($max_value >= 0) {
                        $registratie_to_save['id'] = $registratie_id;
                        $registratie_to_save[$fieldname] = $max_value + 1;
                        $registraties[$updated_registratie_key]['Registratie'][$fieldname] = $max_value + 1;
                        $this->save($registratie_to_save);
                    }
                    break;
                } else {
                    $registratie_to_save['id'] = $registratie_id;
                    $registratie_to_save[$fieldname] = -1;
                    $registraties[$updated_registratie_key]['Registratie'][$fieldname] = -1;
                    $this->save($registratie_to_save);
                }

                // no break
            case 'del':
                $registraties = $this->delKlantFromQueueList($registratie_id, $fieldname, $registraties, $registratie_data);
                break;
            default:
        }

        return $registraties;
    }

    public function delKlantFromShowerList($registratie_id, &$registraties, &$registratie_data)
    {
        $registraties_list = [];

        foreach ($registraties as $key => $registratie) {
            if ($registratie['Registratie']['douche'] > 0) {
                $registraties_list[$registratie['Registratie']['id']] = $registratie['Registratie']['douche'];
            }
        }

        asort($registraties_list);
        $r_to_save = [];

        if ($registratie_data['Registratie']['douche'] > 0) {
            unset($registraties_list[$registratie_id]);
            $r_to_save[$registratie_data['Registratie']['id']]['id'] = $registratie_data['Registratie']['id'];
            $r_to_save[$registratie_data['Registratie']['id']]['douche'] = -1;
            $inc = 1;
            foreach ($registraties_list as $key => $value) {
                $r_to_save[$key]['id'] = $key;
                $r_to_save[$key]['douche'] = $inc;
                ++$inc;
            }
        } elseif (-1 == $registratie_data['Registratie']['douche']) {
            $r_to_save[$registratie_data['Registratie']['id']]['id'] = $registratie_data['Registratie']['id'];
            $r_to_save[$registratie_data['Registratie']['id']]['douche'] = 0;
        }
        $this->saveAll($r_to_save);
        foreach ($registraties as $r_key => $registratie_value) {
            foreach ($r_to_save as $registratie_saved) {
                if ($registratie_value['Registratie']['id'] == $registratie_saved['id']) {
                    $registraties[$r_key]['Registratie']['douche'] = $registratie_saved['douche'];
                }
            }
        }

        return $registraties;
    }

    public function updateShowerList($action, $registratie_id, $locatie_id)
    {
        $registraties = $this->find('all', $this->getRegistratiesConditions($locatie_id));
        $registratie_data = [];
        $updated_registratie_key = null;

        foreach ($registraties as $key => $registratie) {
            if ($registratie['Registratie']['id'] == $registratie_id) {
                $updated_registratie_key = $key;
                $registratie_data['Registratie'] = $registratie['Registratie'];
            }
        }

        if (empty($registratie_data)) {
            return $registraties;
        }

        switch ($action) {
            case 'add':
                $max_value = 0;
                $max_key = 0;
                if (null == $registratie_data['Registratie']['buiten']) {
                    foreach ($registraties as $key => $registratie) {
                        if ($registratie['Registratie']['douche'] > $max_value) {
                            $max_value = $registratie['Registratie']['douche'];
                            $max_key = $key;
                        }
                    }
                    if ($max_value >= 0) {
                        $registratie_to_save['id'] = $registratie_id;
                        $registratie_to_save['douche'] = $max_value + 1;
                        $registraties[$updated_registratie_key]['Registratie']['douche'] = $max_value + 1;
                        $this->save($registratie_to_save);
                    }
                    break;
                } else {
                    $registratie_to_save['id'] = $registratie_id;
                    $registratie_to_save['douche'] = -1;
                    $registraties[$updated_registratie_key]['Registratie']['douche'] = -1;
                    $this->save($registratie_to_save);
                }

                // no break
            case 'del':
                $registraties = $this->delKlantFromShowerList($registratie_id, $registraties, $registratie_data);
                break;
            default:
        }

        return $registraties;
    }

    public function addRegistratie($klant_id = null, $locatie_id = null)
    {
        if ($klant_id && $locatie_id) {
            $registratie_conditions = $this->getRegistratiesConditions($locatie_id);
            $registratie_conditions['conditions']['klant_id'] = $klant_id;
            $registratie_conditions['conditions']['closed'] = false;
            $registratie = $this->find('first', $registratie_conditions);
            if ($registratie) {
                return false;
            }

            $registratie['klant_id'] = $klant_id;
            $registratie['locatie_id'] = $locatie_id;
            $registratie['binnen'] = date('Y-m-d H:i:s', time());

            $prev_cond = $this->getRegistratiesConditions($locatie_id, 'today_inactive');
            $prev_cond['conditions']['klant_id'] = $klant_id;
            $prev_cond['order'] = 'Registratie.created DESC';
            $this->recursive = -1;
            $prev_reg = $this->find('first', $prev_cond);

            $this->create();
            $this->set($registratie);
            $save_prev = false;

            if (!empty($prev_reg)) {
                if ($this->set_checkboxes_from_previous_registration($prev_reg)) {
                    $save_prev = true;
                }
            }

            if ($this->save()) {
                $this->Klant->set_last_registration($klant_id, $this->id);
                if ($save_prev) {
                    $this->create();
                    $this->save($prev_reg);
                }

                return true;
            }
        }

        return false;
    }

    public function set_checkboxes_from_previous_registration(&$previous_registration)
    {
        if (empty($previous_registration['Registratie'])) {
            return;
        }

        $fields = [
            'douche', 'maaltijd', 'veegploeg', 'kleding', 'activering', 'mw', 'gbrv',
        ];

        $changed = false;

        foreach ($fields as $field) {
            $prev_val = $previous_registration['Registratie'][$field];
            if (1 == $prev_val || -1 == $prev_val) {
                $this->set($field, $prev_val);
                $previous_registration['Registratie'][$field] = 0;
                $changed = true;
            }
        }

        return $changed;
    }

    public function setMessages(&$data)
    {
        $next_check = Configure::read('TBC_months_period');

        foreach ($data as $key => $val) {
            if (!isset($val['Klant'])
                || !isset($val['Klant']['laatste_TBC_controle'])
                || empty($val['Klant']['laatste_TBC_controle'])
            ) {
                $data[$key]['Klant']['laatste_TBC_controle_message'] = 'onbekend';
            }
            $dd = $this->Klant->TimeDiffShort(
                $val['Klant']['laatste_TBC_controle'],
                '+ '.$next_check.' months'
            );
            $data[$key]['Klant']['laatste_TBC_controle_message'] = $dd;

            $id = $val['Registratie']['klant_id'];
            $schCount = $this->Klant->Schorsing->countActiveSchorsingenMsg($id);

            $data[$key]['Klant']['active_schorsingen'] = $schCount;

            $unseenOpm = $this->Klant->Opmerking->countUnSeenOpmerkingen($id);
            if (1 === $unseenOpm) {
                $unseenOpm = '1 opmerking';
            } elseif ($unseenOpm > 1) {
                $unseenOpm = $unseenOpm.' opmerkingen';
            } else {
                $unseenOpm = 'geen';
            }
            $data[$key]['Klant']['opmerkingen'] = $unseenOpm;
        }

        $this->Klant->contain;
    }

    public function _buildQuery($locatie_id, &$sort_string)
    {
        $q = 'SELECT * FROM('.
                'SELECT registraties.id, registraties.klant_id, klanten.voornaam, klanten.achternaam '.
                    'FROM registraties '.
                        'INNER JOIN klanten ON klanten.id = registraties.klant_id '.
                        'WHERE locatie_id = '.$locatie_id.
                    ' ORDER by registraties.modified desc) AS Registratie '.
            'GROUP BY klant_id';

        if (strlen($sort_string)) {
            $q .= ' ORDER BY '.$sort_string;
        }
        $q .= ';';

        return $q;
    }

    public function getLocatieName($locatie_id)
    {
        $locatie = $this->Locatie->find('first', [
            'conditions' => ['Locatie.id' => $locatie_id],
            'fields' => ['naam'],
        ]);

        return $locatie['Locatie']['naam'];
    }

    public function getUniqueVisitorsWith4OrMoreRegistrations($conditions)
    {
        $queryData = [
            'recursive' => -1,
            'conditions' => $conditions,
            'fields' => [
                'klant_id',
            ],
            'group' => 'klant_id',
        ];

        $null = null;

        $query = $this->getDataSource()->generateAssociationQuery(
            $this, $null, null, null, null, $queryData, false, $null
        );

        $result = $this->query("
            select count(*) cnt
              from (
                    $query
                    having count(*) >= 4
                   ) a
        ");

        return $result[0][0]['cnt'];
    }

    public function beforeSave($options = [])
    {
        $data = [$this->alias => $this->data[$this->alias]];

        if ($this->id) {
            if (!empty($this->data[$this->alias]['buiten'])) {
                $this->set('closed', true);
            }
        } else {
            $this->set('closed', false);
        }

        return parent::beforeSave($options);
    }
}
