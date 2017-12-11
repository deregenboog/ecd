<?php

class BotKoppelingenController extends AppController
{
    public $name = 'BotKoppelingen';

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
        'BotVerslag' => [
            'fields' => ['*'],
            'Medewerker' => [],
        ],
        'Document' => [
            'conditions' => [
                'group' => 'bto',
                'is_active' => true,
            ],
        ],
    ];

    public function index($klantId = null, $back_on_track_id = null)
    {
        if (!$klantId) {
            $klantId = $this->getParam('klant_id');
        }

        if (!$klantId) {
            $this->flashError(__('Invalid klant_id', true));
            $this->redirect(['controller' => 'back_on_track', 'action' => 'index']);
        }

        if (!$back_on_track_id) {
            $back_on_track_id = $this->getParam('back_on_track_id');
        }

        if (!$back_on_track_id) {
            $this->flashError(__('Invalid back_on_track_id', true));
            $this->redirect(['controller' => 'back_on_track', 'action' => 'index']);
        }

        $bot_koppelingen = $this->BotKoppeling->find('all', [
                'contain' => [],
                'conditions' => ['BotKoppeling.back_on_track_id' => $back_on_track_id],
                'order' => 'startdatum ASC, einddatum ASC',
        ]);

        $klant = $this->BotKoppeling->BackOnTrack->Klant->find('first', [
                'contain' => $this->klant_contain,
                'conditions' => ['Klant.id' => $klantId],
        ]);

        $this->data['BotKoppeling']['back_on_track_id'] = $back_on_track_id;
        $this->data['BotKoppeling']['klant_id'] = $klantId;

        $this->setMedewerkers([], [GROUP_BACK_ON_TRACK_COACH, GROUP_BACK_ON_TRACK_COORDINATOR]);

        $this->set('bot_koppelingen', $bot_koppelingen);
        $this->set('klant', $klant);
    }

    public function add()
    {
        if (!empty($this->data)) {
            $klant_id = $this->data['BotKoppeling']['klant_id'];
            $back_on_track_id = $this->data['BotKoppeling']['back_on_track_id'];

            $this->BotKoppeling->create();

            if ($this->BotKoppeling->save($this->data)) {
                $this->Session->setFlash(__('The bot koppeling has been saved', true));
                $this->redirect(['controller' => 'back_on_track', 'action' => 'view', $this->data['BotKoppeling']['klant_id']]);

                return;
            } else {
                $this->Session->setFlash(__('The bot koppeling could not be saved. Please, try again.', true));
            }
        } else {
            $back_on_track_id = $this->getParam('back_on_track_id');
            $klant_id = $this->getParam('klant_id');
        }

        if (empty($back_on_track_id) || empty($klant_id)) {
            $this->redirect(['controller' => 'back_on_track', 'action' => 'index']);
        }

        $this->setMedewerkers([], [GROUP_BACK_ON_TRACK_COACH, GROUP_BACK_ON_TRACK_COORDINATOR]);
        $this->index($klant_id, $back_on_track_id);

        $this->render('index');
    }

    public function edit($id = null)
    {
        $this->BotKoppeling->recursive = 0;

        $k = $this->BotKoppeling->read(null, $id);

        if (empty($k)) {
            $this->redirect(['controller' => 'back_on_track', 'action' => 'index']);
        }

        $klant_id = $k['BackOnTrack']['klant_id'];
        $back_on_track_id = $k['BotKoppeling']['back_on_track_id'];

        if (!empty($this->data)) {
            $this->BotKoppeling->create();

            if ($this->BotKoppeling->save($this->data)) {
                $this->Session->setFlash(__('The bot koppeling has been saved', true));
                $this->redirect(['controller' => 'back_on_track', 'action' => 'view', $klant_id]);
            } else {
                $this->Session->setFlash(__('The bot koppeling could not be saved. Please, try again.', true));
            }
        }

        if (empty($back_on_track_id) || empty($klant_id)) {
            $this->redirect(['controller' => 'back_on_track', 'action' => 'index']);
        }

        $this->data = $k;

        $this->setMedewerkers([$k['BotKoppeling']['medewerker_id']], [GROUP_BACK_ON_TRACK_COACH, GROUP_BACK_ON_TRACK_COORDINATOR]);

        $this->index($klant_id, $back_on_track_id);

        $this->render('index');
    }

    public function delete($id = null)
    {
        $this->BotKoppeling->recursive = 0;

        $k = $this->BotKoppeling->read(null, $id);

        if (empty($k)) {
            $this->redirect(['controller' => 'back_on_track', 'action' => 'index']);
        }

        $redirect_url = [
            'action' => 'index',
            'klant_id' => $k['BackOnTrack']['klant_id'],
            'back_on_track_id' => $k['BotKoppeling']['back_on_track_id'],
        ];

        if (!$id) {
            $this->Session->setFlash(__('Invalid id for bot koppeling', true));
            $this->redirect($redirect_url);
        }

        if ($this->BotKoppeling->delete($id)) {
            $this->Session->setFlash(__('Bot koppeling deleted', true));
            $this->redirect($redirect_url);
        }

        $this->Session->setFlash(__('Bot koppeling was not deleted', true));

        $this->redirect($redirect_url);
    }
}
