<?php

class BackOnTrackController extends AppController
{
    public $name = 'BackOnTrack';

    public $uses = ['BackOnTrack', 'Klant'];

    public $components = [
            'ComponentLoader',
    ];

    public $klant_contain = [
        'Geslacht' => [
            'fields' => [
                'afkorting',
                'volledig',
            ],
        ],
        'Nationaliteit' => [
            'fields' => [
                'naam',
                'afkorting',
            ],
        ],
        'Geboorteland' => [
            'fields' => [
                'land',
                'AFK2',
                'AFK3',
            ],
        ],
        'Medewerker' => [
            'fields' => [
                'tussenvoegsel',
                'achternaam',
                'voornaam',
            ],
        ],
        'BotVerslag' => [
            'fields' => ['*'],
            'Medewerker' => [],
        ],
        'BackOnTrack' => [
            'BotKoppeling' => [
                'fields' => [
                    'id',
                    'medewerker_id',
                    'back_on_track_id',
                    'startdatum',
                    'einddatum',
                ],
            ],
            'fields' => [
                'id',
                'klant_id',
                'startdatum',
                'einddatum',
                'intakedatum',
            ],
        ],
        'Document' => [
            'conditions' => [
                'group' => 'bto',
                'is_active' => true,
            ],
        ],
    ];

    private function checkPermissions($forKlant)
    {
        if ($this->back_on_track_coordinator) {
            return true;
        }

        $today = strtotime('today');
        $valid = true;

        if (empty($forKlant['BackOnTrack']['startdatum'])) {
            $valid = false;
        }

        if (strtotime($forKlant['BackOnTrack']['startdatum']) > $today
            || (!empty($forKlant['BackOnTrack']['einddatum']) && strtotime($forKlant['BackOnTrack']['einddatum']) < $today)) {
            $valid = false;
        }

        if (!$valid) {
            $this->flashError(__('Invalid back on track', true));
            $this->redirect(['action' => 'index']);
        }

        return true;
    }

    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->back_on_track_coach = false;
        if (array_key_exists(GROUP_BACK_ON_TRACK_COACH, $this->userGroups)) {
            $this->back_on_track_coach = true;
        }

        $this->set('back_on_track_coach', $this->back_on_track_coach);

        $this->back_on_track_coordinator = false;

        if (array_key_exists(GROUP_BACK_ON_TRACK_COORDINATOR, $this->userGroups)) {
            $this->back_on_track_coordinator = true;
        }

        if (array_key_exists(GROUP_DEVELOP, $this->userGroups)) {
            $this->back_on_track_coordinator = true;
        }

        if (array_key_exists(GROUP_ADMIN, $this->userGroups)) {
            $this->back_on_track_coordinator = true;
        }

        $this->set('back_on_track_coordinator', $this->back_on_track_coordinator);
    }

    public function index()
    {
        $this->ComponentLoader->load('Filter');

        $this->paginate = [
            'contain' => [
                'LasteIntake' => [
                    'fields' => [
                        'locatie1_id',
                        'locatie2_id',
                        'locatie3_id',
                        'datum_intake',
                    ],
                ],
                'Intake' => [
                    'fields' => [
                        'datum_intake',
                        'id',
                    ],
                ],
                'Geslacht',
                'BackOnTrack',
            ],
        ];

        $show_all = false;

        if (isset($this->data['Klant']['show_all']) && !empty($this->data['Klant']['show_all'])) {
            $show_all = true;
        }

        if (true && (!$this->back_on_track_coordinator || ($this->back_on_track_coordinator && !$show_all))) {
            $today = date('Y-m-d');
            $this->Filter->filterData['BackOnTrack.startdatum <='] = $today;
            $this->Filter->filterData['OR']['BackOnTrack.einddatum >='] = $today;
            $this->Filter->filterData['OR']['BackOnTrack.einddatum'] = null;
        }

        $this->setMedewerkers();

        $klanten = $this->paginate('Klant', $this->Filter->filterData);

        $klanten = $this->Klant->LasteIntake->completeKlantenIntakesWithLocationNames($klanten);

        $rowOnclickUrl = [
            'controller' => 'back_on_track',
            'action' => 'view',
        ];

        $this->set('back_on_track_coordinator', $this->back_on_track_coordinator);

        $this->set(compact('klanten', 'rowOnclickUrl'));

        if ($this->RequestHandler->isAjax()) {
            $this->render('/elements/klantenlijst', 'ajax');
        }
    }

    public function view($klant_id = null)
    {
        $tmp_klant_id = $this->getParam('klant_id', 0);

        if (!empty($tmp_klant_id)) {
            $klant_id = $tmp_klant_id;
        }

        if (!$klant_id) {
            $this->flashError(__('Invalid back on track', true));
            $this->redirect(['action' => 'index']);
        }

        $this->Klant->contain = $this->klant_contain;

        $klant = $this->Klant->find('first', [
                'contain' => $this->klant_contain,
                'conditions' => ['Klant.id' => $klant_id],
        ]);

        $this->checkPermissions($klant);

        $this->set('klant', $klant);

        $this->setMedewerkers();

        $contactTypes = $this->Klant->BotVerslag->getContactTypes();

        $this->set('contactTypes', $contactTypes);
    }

    public function add()
    {
        $klant_id = $this->getParam('klant_id');

        if (!empty($this->data)) {
            $this->BackOnTrack->create();

            if ($this->BackOnTrack->save($this->data)) {
                $this->flash(__('De gegevens zijn opgeslagen', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The back on track could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($klantId = null)
    {
        $klantId = $this->getParam('BackOnTrack.klant_id', 0);

        if (!$klantId) {
            $this->flashError(__('Invalid back on track', true));
            $this->redirect(['action' => 'index']);
        }

        $klant = $this->Klant->find('first', [
            'contain' => $this->klant_contain,
            'conditions' => ['Klant.id' => $klantId],
        ]);

        $this->checkPermissions($klant);

        if (!empty($this->data)) {
            $this->BackOnTrack->create();

            if ($this->BackOnTrack->save($this->data)) {
                $this->flash(__('De gegevens zijn opgeslagen', true));
                $this->redirect(['action' => 'view', $klantId]);
            } else {
                $this->flashError(__('The back on track could not be saved. Please, try again.', true));
            }
        } else {
            $this->data = $this->BackOnTrack->find('first', [
                'conditions' => [
                    'BackOnTrack.klant_id' => $klantId,
                ],
            ]);
        }

        $this->set('klant', $klant);
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid back on track', true));
            $this->redirect(['action' => 'index']);
        }

        if ($this->BackOnTrack->delete($id)) {
            $this->Session->setFlash(__('Back on track deleted', true));
            $this->redirect(['action' => 'index']);
        }

        $this->Session->setFlash(__('Back on track was not deleted', true));

        $this->redirect(['action' => 'index']);
    }

    public function upload($klantId = null)
    {
        if (empty($klantId)) {
            $klantId = $this->data['Klant']['id'];
        }

        $group = 'bto';

        $klant = $this->Klant->find('first', [
            'contain' => $this->klant_contain,
            'conditions' => ['Klant.id' => $klantId],
        ]);

        $this->checkPermissions($klant);

        $this->set('klant', $klant);

        if (!empty($this->data)) {
            $this->data['Document']['foreign_key'] = $klantId;
            $this->data['Document']['model'] = $this->Klant->name;
            $this->data['Document']['group'] = $group;
            $this->data['Document']['user_id'] = $this->Session->read('Auth.Medewerker.id');

            if (empty($this->data['Document']['title'])) {
                $this->data['Document']['title'] = $this->data['Document']['file']['name'];
            }

            if ($this->Klant->Document->save($this->data)) {
                $this->redirect([
                    'action' => 'view',
                    $klantId,
                ]);
            } else {
                $this->flashError(__('The document could not be saved. Please, try again.', true));
            }
        } else {
            $this->data = $klant;
            $this->data['Document']['group'] = $group;
        }
    }
}
