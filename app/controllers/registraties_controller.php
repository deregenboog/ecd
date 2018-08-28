<?php


class RegistratiesController extends AppController
{
    public $name = 'Registraties';
    public $components = ['Filter', 'RequestHandler', 'Session'];
    public $uses = ['Registratie', 'Klant'];

    public function isAuthorized()
    {
        if (!parent::isAuthorized()) {
            return false;
        }

        $user_groups = $this->AuthExt->user('Group');
        $volunteers = Configure::read('ACL.volunteers');

        if (empty($user_groups)) {
            return false;
        }

        $action_locaties_filter = [
            'index' => 0,
            'ajaxUpdateShowerList' => 1,
            'ajaxUpdateMWList' => 1,
            'registratieCheckOut' => 1,
            'registratieCheckOutAll' => 0,
            'ajaxAddRegistratie' => 1,
            'delete' => 1,
            'sortRegistraties' => 0,
            'setRegistraties' => 0,
        ];

        $username = $this->AuthExt->user('username');

        if (isset($volunteers[$username])) {
            if (isset($action_locaties_filter[$this->action])) {
                if (!empty($this->params['pass'])) {
                    if (!isset($this->params['pass'][$action_locaties_filter[$this->action]])
                        || ($this->params['pass'][$action_locaties_filter[$this->action]] != $volunteers[$username])
                    ) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function index($locatie_id = null)
    {
        if ($locatie_id && $locatie = $this->Registratie->Locatie->getById($locatie_id)) {
            $this->disableCache();

            $conditions = $this->Filter->filterData;
            $conditions[] = ['LasteIntake.toegang_inloophuis' => 1];
            $conditions[] = ['OR' => [
                'Klant.overleden NOT' => 1,
                'Klant.overleden' => null,
            ]];

            if (!empty($locatie['gebruikersruimte'])) { // Blaka Watra Gebruikersruimte , Amoc Gebruikersruimte , Princehof
                $conditions[] = ['LasteIntake.locatie1_id' => $locatie_id];
            } elseif (17 == $locatie['id']) { // Vrouwen Nacht Opvang
                $conditions[]['Geslacht.afkorting'] = 'V';
                $conditions[]['LasteIntake.toegang_vrouwen_nacht_opvang'] = 1;
            } elseif (5 == $locatie['id']) { // Amoc
                $conditions[] = ['OR' => [
                    'LasteIntake.amoc_toegang_tot >=' => date('Y-m-d'),
                    'LasteIntake.amoc_toegang_tot' => null,
                ]];
            } elseif (12 == $locatie['id']) { // Nachtopvang De Regenboog Groep
            } else { // Rest
                $conditions[] = ['OR' => [
                    'LasteIntake.verblijfstatus_id NOT ' => 7,
                    // @see https://github.com/deregenboog/ecd/issues/249
                    [
                        'LasteIntake.verblijfstatus_id' => 7,
                        'DATE_ADD(Klant.first_intake_date, INTERVAL 3 MONTH) < now()',
                        "Klant.first_intake_date < '2017-06-01'",
                    ],
                    [
                        'LasteIntake.verblijfstatus_id' => 7,
                        'DATE_ADD(Klant.first_intake_date, INTERVAL 6 MONTH) < now()',
                        "Klant.first_intake_date >= '2017-06-01'",
                    ],
                ]];
            }

            $this->log($conditions, 'registratie');

            $this->paginate['Klant'] = [
                'joins' => [
                    [
                        'table' => 'inloop_dossier_statussen',
                        'type' => 'INNER',
                        'conditions' => [
                            'inloop_dossier_statussen.id = Klant.huidigeStatus_id',
                            'inloop_dossier_statussen.class' => 'Aanmelding',
                        ],
                    ],
                ],
                'contain' => [
                    'Geslacht' => [
                        'fields' => [
                            'afkorting',
                            'volledig',
                        ],
                    ],
                    'LasteIntake' => [
                        'fields' => [
                            'locatie1_id',
                            'locatie2_id',
                            'locatie3_id',
                            'datum_intake',
                        ],
                    ],
                ],
                'conditions' => $conditions,
                'order' => [
                    'Klant.achternaam' => 'asc',
                    'Klant.voornaam' => 'asc',
                ],
            ];

            $this->Klant->recursive = -1;

            $klanten = $this->paginate('Klant');
            $klanten = $this->Klant->LasteIntake->completeKlantenIntakesWithLocationNames($klanten);
            $klanten = $this->Klant->completeVirtualFields($klanten);

            $this->Klant->Schorsing->get_schorsing_messages($klanten, $locatie_id);

            $this->set('klanten', $klanten);
            $this->set('add_to_list', 1);
            $this->set('locatie_id', $locatie_id);

            $loc_name = $locatie['naam'];
            $this->set('locatie', $locatie);
            $this->set('locatie_name', $loc_name);
            $this->setRegistraties($locatie_id);
            $this->set('locaties', $this->Registratie->Locatie->find('list'));

            if ($this->RequestHandler->isAjax()) {
                $this->render('/elements/registratie_klantenlijst', 'ajax');
            }
        } else {
            $this->set('locaties', $this->Registratie->Locatie->locaties(['maatschappelijkwerk' => 0]));
            $this->render('locaties');
        }
    }

    public function ajaxUpdateShowerList($action, $locatie_id = null, $registratie_id = null)
    {
        if ($this->RequestHandler->isAjax()) {
            $registraties = &$this->Registratie->updateShowerList($action, $registratie_id, $locatie_id);
            $this->setRegistraties($locatie_id);
            $this->set('locatie_id', $locatie_id);
            $this->Registratie->Locatie->id = $locatie_id;
            $loc_name = $this->Registratie->Locatie->field('Naam');
            $this->set('locatie_name', $loc_name);
            $this->render('/elements/registratielijst', 'ajax');
        } else {
            $this->autoRender = false;
        }
    }

    public function ajaxUpdateQueueList($action, $fieldname = 'mw', $locatie_id = null, $registratie_id = null)
    {
        if ($this->RequestHandler->isAjax()) {
            $registraties = &$this->Registratie->updateQueueList($action, $fieldname, $registratie_id, $locatie_id);
            $this->setRegistraties($locatie_id);
            $this->set('locatie_id', $locatie_id);
            $this->Registratie->Locatie->id = $locatie_id;
            $loc_name = $this->Registratie->Locatie->field('Naam');
            $this->set('locatie_name', $loc_name);
            $this->render('/elements/registratielijst', 'ajax');
        } else {
            $this->autoRender = false;
        }
    }

    public function ajaxUpdateRegistratie($registratie_id = null)
    {
        if ($this->RequestHandler->isAjax()) {
            $this->set('fieldname', key($this->data['Registratie']));
            $this->data['Registratie']['id'] = $registratie_id;
            $this->Registratie->save($this->data);
            $this->set('registratie', $this->Registratie->find('first', [
                'conditions' => [
                    'Registratie.id' => $registratie_id,
                ],
                'contain' => ['Klant' => ['Intake']],
            ]));
            $this->render('/elements/registratie_checkboxes', 'ajax');
        } else {
            $this->autoRender = false;
        }
    }

    public function ajaxShowHistory($locatie_id)
    {
        if (!isset($this->data['History']['history_limit']) ||
            empty($this->data['History']['history_limit'])
        ) {
            $day_limit = 0;
        } else {
            $day_limit = $this->data['History']['history_limit'];
        }

        $this->setRegistraties($locatie_id, $day_limit);
        $this->Registratie->Locatie->id = $locatie_id;
        $locatie_name = $this->Registratie->Locatie->field('Naam');
        $this->set(compact('locatie_name', 'locatie_id'));

        $this->render('/elements/registratielijst', 'ajax');
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid registratie', true));
            $this->redirect(['action' => 'index']);
        }

        $this->set('registratie', $this->Registratie->read(null, $id));
    }

    public function registratieCheckOut($registratie_id = null, $locatie_id = null)
    {
        if ($registratie_id) {
            if ($this->Registratie->registratieCheckOut($registratie_id)) {
            }
        }

        $this->setRegistraties($locatie_id);
        $this->set('locatie_id', $locatie_id);
        $this->Registratie->Locatie->id = $locatie_id;
        $loc_name = $this->Registratie->Locatie->field('Naam');
        $this->set('locatie_name', $loc_name);
        $this->render('/elements/registratielijst', 'ajax');
    }

    public function registratieCheckOutAll($locatie_id = null)
    {
        if ($locatie_id) {
            $conditions = ['conditions' => [
                        'locatie_id' => $locatie_id,
                        'buiten' => null, ],
                        'contain' => ['Klant'], ];
            $registraties = $this->Registratie->find('list', $conditions);
            foreach ($registraties as $registratie_id) {
                $this->Registratie->registratieCheckOut($registratie_id);
            }
        }

        $this->setRegistraties($locatie_id);
        $this->set('locatie_id', $locatie_id);
        $this->Registratie->Locatie->id = $locatie_id;
        $loc_name = $this->Registratie->Locatie->field('Naam');
        $this->set('locatie_name', $loc_name);
        $this->render('/elements/registratielijst', 'ajax');
    }

    public function ajaxAddRegistratie($klant_id = null, $locatie_id = null)
    {
        if (1 || $this->RequestHandler->isAjax()) {
            if ($klant_id && $locatie_id) {
                $this->Registratie->checkoutKlantFromAllLocations($klant_id);
                $this->Registratie->addRegistratie($klant_id, $locatie_id);
            }
            $this->setRegistraties($locatie_id);
            $this->set('locatie_id', $locatie_id);
            $this->Registratie->Locatie->id = $locatie_id;
            $loc_name = $this->Registratie->Locatie->field('Naam');
            $this->set('locatie_name', $loc_name);
            $this->render('/elements/registratielijst', 'ajax');
        } else {
            $this->render('/elements/registratielijst');
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid registratie', true));
            $this->redirect(['action' => 'index']);
        }

        if (!empty($this->data)) {
            if ($this->Registratie->save($this->data)) {
                $this->flashError(__('The registratie has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The registratie could not be saved. Please, try again.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->Registratie->read(null, $id);
        }

        $locaties = $this->Registratie->Locatie->find('list');
        $klanten = $this->Registratie->Klant->find('list');
        $this->set(compact('locaties', 'klanten'));
    }

    public function delete($id = null, $locatie_id = null)
    {
        if (empty($id) || empty($locatie_id)) {
            $this->render('/elements/registratielijst');

            return;
        }

        if ($this->RequestHandler->isAjax()) {
            if (!$id) {
                $this->flashError(__('Invalid id for registratie', true));
            }

            $this->Registratie->recursive = -1;
            $registratie = $this->Registratie->findById($id);
            $this->Registratie->removeKlantFromAllQueueLists($registratie);
            $klant_id = $registratie['Registratie']['klant_id'];

            if ($this->Registratie->delete($id)) {
                $this->Registratie->Klant->set_last_registration($klant_id);
            }

            $this->setRegistraties($locatie_id);
            $this->set('locatie_id', $locatie_id);
            $this->Registratie->Locatie->id = $locatie_id;
            $loc_name = $this->Registratie->Locatie->field('Naam');
            $this->set('locatie_name', $loc_name);
            $this->render('/elements/registratielijst', 'ajax');
        } else {
            $this->render('/elements/registratielijst');
        }
    }

    public function setRegistraties($locatie_id, $history_limit = 0)
    {
        $gebruikersruimte_registraties = [];
        $active_registraties = [];

        $this->Registratie->getActiveRegistraties(
            $active_registraties,
            $gebruikersruimte_registraties,
            $locatie_id
        );

        $past_registraties = $this->Registratie->getRecentlyUnregistered(
            $locatie_id,
            $history_limit,
            $active_registraties,
            $gebruikersruimte_registraties
        );

        $this->Registratie->setMessages($active_registraties);
        $this->Registratie->setMessages($gebruikersruimte_registraties);
        $this->Registratie->setMessages($past_registraties);

        $this->set('active_registraties', $active_registraties);
        $this->set('gebruikersruimte_registraties', $gebruikersruimte_registraties);
        $this->set('past_registraties', $past_registraties);
        $this->set('total_registered_clients', count($active_registraties));
    }

    public function set_last_registrations()
    {
        $this->Registratie->Klant->recursive = -1;
        $klanten = $this->Registratie->Klant->find('all', [
            'fields' => ['id', 'laatste_registratie_id'],
        ]);

        foreach ($klanten as $klant) {
            if (empty($klant['Klant']['laatste_registratie_id'])) {
                $this->Registratie->Klant->set_last_registration($klant['Klant']['id']);
            }
        }
    }

    public function jsonCanRegister($klant_id, $locatie_id, $h = 1)
    {
        $this->Klant->set_registration_virtual_fields();
        $this->Klant->contain[] = 'LaatsteRegistratie';
        $klant = &$this->Klant->find('first', [
            'conditions' => ['Klant.id' => $klant_id],
        ]);
        $this->Registratie->Locatie->recursive = -1;
        $location = $this->Registratie->Locatie->read(null, $locatie_id);

        $jsonVar = [
            'confirm' => false,
            'allow' => true,
            'message' => '',
        ];

        $sep = '';
        $separator = PHP_EOL.PHP_EOL;

        if (!empty($location['Locatie']['gebruikersruimte'])) {
            $this->loadModel('Registratie');
            $laatsteRegistratie = $this->Registratie->find('first', [
                'conditions' => [
                    'klant_id' => $klant['Klant']['id'],
                    'locatie_id' => $location['Locatie']['id'],
                ],
                'order' => ['Registratie.binnen' => 'DESC'],
                'recursive' => -1,
            ]);
            if (new \DateTime($laatsteRegistratie['Registratie']['binnen']) < new \DateTime('-2 months')
                && ($location['Locatie']['id'] != $klant['LasteIntake']['locatie1_id']
                    || new \DateTime($klant['LasteIntake']['datum_intake']) < new \DateTime('-2 months')
                )
            ) {
                $jsonVar['allow'] = false;
                $jsonVar['message'] = 'Langer dan twee maanden niet geweest. Opnieuw aanmelden via het maatschappelijk werk.';
                goto render;
            }
        }

        if (!empty($location['Locatie']['gebruikersruimte'])
            && !empty($klant['LasteIntake']['mag_gebruiken'])
            && !$klant['Klant']['laatste_TBC_controle']
        ) {
            $jsonVar['allow'] = false;
            $jsonVar['message'] = 'Deze klant heeft geen TBC controle gehad en kan niet worden ingecheckt bij een locatie met een gebruikersruimte.';
            goto render;
        }

        $this->loadModel('LocatieTijd');
        if (!$this->LocatieTijd->isOpen($locatie_id, time())) {
            $jsonVar['allow'] = false;
            $jsonVar['message'] = 'Deze locatie is nog niet open, klant kan nog niet inchecken!';
            goto render;
        }

        if (!empty($klant['LaatsteRegistratie']['id'])) {
            if (empty($klant['LaatsteRegistratie']['buiten'])) {
                if ($klant['LaatsteRegistratie']['locatie_id'] == $locatie_id) {
                    $jsonVar['allow'] = false;
                    $jsonVar['message'] .= $sep.'Deze klant is op dit moment al ingecheckt op deze locatie.';
                } else {
                    $jsonVar['confirm'] = true;
                    $jsonVar['message'] .= $sep.'Deze klant is op dit moment al ingecheckt op een andere locatie. Toch inchecken?';
                    $sep = $separator;
                }

                $sep = $separator;
            } else {
                $last_check_out = new
                    DateTime($klant['LaatsteRegistratie']['buiten']);

                $now = new DateTime();
                $d = $last_check_out->diff($now);

                if ($d->h < $h && 0 == $d->d && 0 == $d->m && 0 == $d->y) {
                    $jsonVar['message'] .= $sep.
            __('This client has been checked out less than an hour ago. '.
                'Are you sure you want to register him/her again?', true);

                    $jsonVar['confirm'] = true;
                    $sep = $separator;
                }
            }
        }

        if ($jsonVar['allow']) {
            if ($klant['Klant']['new_intake_needed'] < 0) {
                $jsonVar['message'] .= $sep.'Let op: deze persoon heeft momenteel een verlopen intake (> 1 jaar geleden). Toch inchecken?';
                $sep = $separator;
                $jsonVar['confirm'] = true;
            }

            $actieveSchorsingen = $this->Registratie->Klant->Schorsing->getActiveSchorsingen($klant_id);
            foreach ($actieveSchorsingen as $actieveSchorsing) {
                foreach ($actieveSchorsing['Locatie'] as $actieveSchorsingLocatie) {
                    if ($location['Locatie']['id'] == $actieveSchorsingLocatie['id']) {
                        $jsonVar['message'] .= $sep.'Let op: deze persoon is momenteel op deze locatie geschorst. Toch inchecken?';
                        $sep = $separator;
                        $jsonVar['confirm'] = true;
                    }
                }
            }

            if ('Ja' == $klant['Klant']['new_TBC_check_needed'] && 1 == $location['Locatie']['tbc_check']) {
                $jsonVar['message'] .= $sep.'Let op: deze persoon heeft een nieuwe TBC-check nodig. Toch inchecken?';
                $jsonVar['confirm'] = true;
                $sep = $separator;
            }

            if (count($klant['Opmerking']) > 0) {
                $laatsteOpmerking = end($klant['Opmerking']);
                if (!$laatsteOpmerking['gezien']) {
                    $datum = new DateTime($laatsteOpmerking['created']);
                    $jsonVar['message'] .= $sep.'Laatste opmerking ('.$datum->format('d-m-Y').'): '.$laatsteOpmerking['beschrijving'];
                    $jsonVar['confirm'] = true;
                    $sep = $separator;
                }
            }
        }

        render:
            $this->set(compact('jsonVar'));

        $this->render('/elements/json', 'ajax');
    }

    public function jsonSimpleCheckboxToggle($fieldname, $registratie_id)
    {
        $this->Registratie->recursive = -1;
        $this->Registratie->Id = $registratie_id;

        $record = $this->Registratie->read($fieldname, $registratie_id);

        if (empty($record)) {
            $jsonVar = 'error';
            $this->set(compact('jsonVar'));
            $this->render('/elements/json', 'ajax');

            return;
        }

        $prev_val = (int) ($record['Registratie'][$fieldname]);

        if ('douche' == $fieldname || 'mw' == $fieldname) {
            $new_val = -1 - $prev_val; //we asume that DB data is correct
        } else {
            $new_val = !($prev_val);
        }

        $this->Registratie->set($fieldname, $new_val);

        if ($this->Registratie->save()) {
            $jsonVar = ['new_val' => $new_val, 'prev_val' => $prev_val];
        } else {
            $jsonVar = ['new_val' => $prev_val, 'prev_val' => $prev_val];
        }

        $this->set(compact('jsonVar'));
        $this->render('/elements/json', 'ajax');
    }
}
