<?php

use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Form\IzDeelnemerSelectieType;
use IzBundle\Form\IzEmailMessageType;
use Doctrine\Common\Collections\ArrayCollection;
use IzBundle\Form\MatchingKlantType;
use IzBundle\Entity\MatchingKlant;
use IzBundle\Form\MatchingVrijwilligerType;
use IzBundle\Entity\MatchingVrijwilliger;

class IzDeelnemersController extends AppController
{
    public $name = 'IzDeelnemers';

    public $components = ['ComponentLoader'];

    public function beforeFilter()
    {
        $this->IzDeelnemer->params = $this->params;
        parent::beforeFilter();
    }

    private function check_persoon_model($persoon_model)
    {
        if (!in_array($persoon_model, ['Klant', 'Vrijwilliger'])) {
            $this->Session->setFlash(__('IZ deelnemer niet gevonden', true));
            $this->redirect(['controller' => 'iz_klanten']);
        }

        if (!isset($this->{$persoon_model})) {
            $this->loadModel($persoon_model);
        }

        return $persoon_model;
    }

    private function setMetadata($id = null, $persoon_model = null, $foreign_key = null)
    {
        $iz_deelnemer = null;
        $is_afgesloten = false;

        if (!empty($id)) {
            $iz_deelnemer = $this->IzDeelnemer->getAllById($id);
            $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];
            $foreign_key = $iz_deelnemer['IzDeelnemer']['foreign_key'];

            if (!empty($iz_deelnemer['IzDeelnemer']['datumafsluiting']) &&
                    strtotime(date('Y-m-d')) >= strtotime($iz_deelnemer['IzDeelnemer']['datumafsluiting'])) {
                $is_afgesloten = true;
            }
        }

        $iz_intake = $this->IzDeelnemer->IzIntake->find('first', [
            'conditions' => ['iz_deelnemer_id' => $id],
            'contain' => [],
        ]);

        $this->setMedewerkers();

        $persoon_model = $this->check_persoon_model($persoon_model);
        $persoon = $this->{$persoon_model}->getAllById($foreign_key);

        $geslachten = $this->{$persoon_model}->Geslacht->find('list');
        $landen = $this->{$persoon_model}->Geboorteland->find('list');

        $nationaliteiten = $this->{$persoon_model}->Nationaliteit->find('list');

        $werkgebieden = Configure::read('Werkgebieden');

        $persoon['IzDeelnemer']['IzDeelnemerDocument'] = [];

        if (!empty($iz_deelnemer['IzDeelnemerDocument'])) {
            $persoon['IzDeelnemer']['IzDeelnemerDocument'] = $iz_deelnemer['IzDeelnemerDocument'];
        }

        $this->set(compact(
            'id',
            'persoon',
            'persoon_model',
            'project_id',
            'foreign_key',
            'geslachten',
            'landen',
            'nationaliteiten',
            'medewerkers',
            'werkgebieden',
            'iz_intake',
            'iz_deelnemer',
            'is_afgesloten'
        ));
    }

    public function view($persoon_model = 'Klant', $foreign_key = null)
    {
        if (empty($foreign_key)) {
            $this->redirect('/');
        }

        $iz_deelnemer = $this->IzDeelnemer->find('first', [
            'conditions' => [
                'model' => $persoon_model,
                'foreign_key' => $foreign_key,
            ],
            'contain' => [],
            'fields' => ['id'],
        ]);

        $id = null;

        if (!empty($iz_deelnemer)) {
            $id = $iz_deelnemer['IzDeelnemer']['id'];
        }

        $this->redirect(['action' => 'toon_aanmelding', $persoon_model, $foreign_key, $id]);
    }

    public function toon_aanmelding($persoon_model, $foreign_key, $id = null)
    {
        $this->aanmelding($persoon_model, $foreign_key, $id = null);
    }

    public function aanmelding($persoon_model, $foreign_key, $id = null)
    {
        $this->loadModel('IzOntstaanContact');
        $this->loadModel('IzViaPersoon');

        if (!empty($this->data)) {
            if (null == $id) {
                $this->data['IzDeelnemer']['model'] = $persoon_model;
                $this->data['IzDeelnemer']['foreign_key'] = $foreign_key;
            } else {
                $this->data['IzDeelnemer']['id'] = $id;
                unset($this->data['IzDeelnemer']['model']);
                unset($this->data['IzDeelnemer']['foreign_key']);
            }

            if (!empty($this->data['IzDeelnemer']['project_id'])) {
                foreach ($this->data['IzDeelnemer']['project_id'] as $project_id) {
                    $tmp = ['iz_project_id' => $project_id];

                    if (!empty($id)) {
                        $tmp['iz_deelnemer_id'] = $id;
                    }

                    $this->data['IzDeelnemersIzProject'][] = $tmp;
                }
            }

            $this->IzDeelnemer->begin();
            $this->IzDeelnemer->create();

            $saved = false;

            while (true) {
                if (!empty($id)) {
                    if (!$this->IzDeelnemer->IzDeelnemersIzProject->deleteAll(['iz_deelnemer_id' => $id])) {
                        break;
                    }
                }

                $retval = $this->IzDeelnemer->saveAll($this->data, ['atomic' => false]);

                if (is_saved($retval)) {
                    $saved = true;
                    $id = $this->IzDeelnemer->id;
                }

                break;
            }

            if ($saved) {
                $this->Session->setFlash(__('De IZ aanmelding is opgeslagen', true));
                $this->IzDeelnemer->commit();
                $this->redirect(['action' => 'toon_aanmelding', $persoon_model, $foreign_key, $id]);
            } else {
                $this->IzDeelnemer->rollback();
                $this->Session->setFlash(__('De IZ aanmelding kan niet worden opgeslagen.', true));
            }
        } else {
            if (empty($id)) {
                $iz = $this->IzDeelnemer->find('first', [
                    'conditions' => [
                        'model' => $persoon_model,
                        'foreign_key' => $foreign_key,
                    ],
                    'fields' => ['id'],
                    'contain' => [],
                ]);

                if (!empty($iz)) {
                    $id = $iz['IzDeelnemer']['id'];
                }
            }

            if (!empty($id)) {
                $this->data = $this->IzDeelnemer->getAllById($id);

                $this->data['IzDeelnemer']['project_id'] = Set::ClassicExtract($this->data, 'IzDeelnemersIzProject.{n}.iz_project_id');
            }
        }

        $diensten = [];
        if ('Klant' == $persoon_model) {
            $diensten = $this->IzDeelnemer->Klant->diensten($foreign_key, $this->getEventDispatcher());
        }
        $this->set('diensten', $diensten);

        $ontstaanContactList = $this->IzOntstaanContact->ontstaanContactList();
        $viaPersoon = $this->IzViaPersoon->viaPersoon($this->data['IzDeelnemer']['binnengekomen_via']);

        $projectlists = $this->IzDeelnemer->IzDeelnemersIzProject->IzProject->projectLists();
        $this->set(compact('projectlists', 'ontstaanContactList', 'persoon', 'viaPersoon'));
        $this->setMetadata($id, $persoon_model, $foreign_key);
        $this->render('view');
    }

    public function toon_intakes($id)
    {
        $this->intakes($id);
    }

    public function intakes($id)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->IzDeelnemer->getById($id);
        if (empty($iz_deelnemer['IzDeelnemer'])) {
            $this->Session->setFlash(__('ID bestaat niet', true));
            $this->redirect(['action' => 'index']);
        }

        $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];
        $foreign_key = $iz_deelnemer['IzDeelnemer']['foreign_key'];

        $iz_intake = $this->IzDeelnemer->IzIntake->find('first', [
            'conditions' => ['iz_deelnemer_id' => $id],
            'contain' => [],
        ]);

        // get ZRM associated with intake
        $this->loadModel(ZrmReport::class);
        foreach (ZrmReport::getZrmReportModels() as $zrmReportModel) {
            $this->loadModel($zrmReportModel);
            $zrmReport = $this->{$zrmReportModel}->get_zrm_report('IzIntake', $iz_intake['IzIntake']['id']);
            if ($zrmReport) {
                $zrmData = $this->{$zrmReportModel}->zrm_data();
                break;
            }
        }

        if (!isset($zrmData)) {
            $zrmReportModel = $this->ZrmReport->getZrmReportModel();
            $zrmData = $this->{$zrmReportModel}->zrm_data();
        }

        if (!empty($this->data)) {
            if (!empty($iz_intake)) {
                $this->data['IzIntake']['id'] = $iz_intake['IzIntake']['id'];
            }
            $this->data['IzIntake']['iz_deelnemer_id'] = $id;

            $this->IzDeelnemer->begin();

            $retval = $this->IzDeelnemer->IzIntake->save($this->data['IzIntake']);
            $saved = false;

            if ($retval) {
                if ($iz_deelnemer['IzDeelnemer']['model'] == 'Klant') {
                    $this->data[$zrmReportModel]['model'] = 'IzIntake';
                    $this->data[$zrmReportModel]['foreign_key'] = $this->IzDeelnemer->IzIntake->id;
                    $this->data[$zrmReportModel]['klant_id'] = $foreign_key;
                    $this->{$zrmReportModel}->create();
                    if ($this->{$zrmReportModel}->save($this->data)) {
                        $saved = true;
                    }

                    $this->loadModel('GroepsactiviteitenIntake');
                    if ($saved && !$this->GroepsactiviteitenIntake->Add2Intake('Klant', $foreign_key, $this->data['IzIntake'])) {
                        $saved = false;
                    }

                    $this->loadModel('GroepsactiviteitenGroepenKlant');
                    if ($saved && 'Klant' == $persoon_model && !$this->GroepsactiviteitenGroepenKlant->Add2Group($foreign_key, 19)) {
                        $saved = false;
                    }
                } else {
                    $saved = true;
                }
            }

            if ($saved) {
                $this->Session->setFlash(__('De IZ intake is opgeslagen', true));
                $this->IzDeelnemer->commit();
                $this->redirect(['action' => 'toon_intakes', $id]);
            } else {
                $this->IzDeelnemer->rollback();
                $this->Session->setFlash(__('De IZ intake kan niet worden opgeslagen.', true));
            }
        } else {
            $this->data = $iz_intake;
            $this->data[$zrmReportModel] = [];

            if (!empty($iz_intake)) {
                $zrm = $zrmReport;
                $this->data[$zrmReportModel] = $zrm[$zrmReportModel];
            }
        }

        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);
        $persoon = $this->{$persoon_model}->getAllById($foreign_key);

        $diensten = [];
        if ('Klant' == $persoon_model) {
            $diensten = $this->IzDeelnemer->Klant->diensten($foreign_key, $this->getEventDispatcher());
        }
        $this->set('diensten', $diensten);

        $this->set(compact('id', 'persoon', 'persoon_model', 'zrmData', 'zrmReportModel'));
        $this->setMedewerkers();
        $this->setMetadata($id);

        $this->render('view');
    }

    public function verslagen_persoon($id)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->IzDeelnemer->getById($id);

        if (empty($iz_deelnemer['IzDeelnemer'])) {
            $this->Session->setFlash(__('ID bestaat niet', true));
            $this->redirect('/');
        }

        $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];
        $foreign_key = $iz_deelnemer['IzDeelnemer']['foreign_key'];

        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);
        $persoon = $this->{$persoon_model}->getAllById($foreign_key);

        $conditions = [
            'IzVerslag.iz_deelnemer_id' => $iz_deelnemer['IzDeelnemer']['id'],
            'IzVerslag.iz_koppeling_id' => null,
        ];

        $iz_koppeling = null;
        $other_persoon = null;

        $verslagen = $this->IzDeelnemer->IzVerslag->find('all', [
            'conditions' => $conditions,
            'contain' => [],
            'order' => 'created DESC',
        ]);

        $diensten = [];
        if ('Klant' == $persoon_model) {
            $diensten = $this->IzDeelnemer->Klant->diensten($foreign_key, $this->getEventDispatcher());
        }
        $this->set('diensten', $diensten);

        $this->set(compact('verslagen', 'persoon', 'persoon_model', 'id', 'iz_deelnemer', 'iz_koppeling', 'other_persoon'));
        $this->setMedewerkers();
        $this->setMetadata($id);
        $this->render('view');
    }

    private function getIzDeelnemer($id)
    {
        $izDeelnemer = $this->IzDeelnemer->getById($id);

        if (!$izDeelnemer) {
            $this->Session->setFlash(__('IZ deelnemer niet gevonden', true));
            $this->redirect(['controller' => 'iz_klanten']);
        }

        $this->check_persoon_model($izDeelnemer['model']);

        $diensten = [];
        if ('Klant' === $izDeelnemer['model']) {
            $diensten = $this->IzDeelnemer->Klant->diensten($izDeelnemer['foreign_key'], $this->getEventDispatcher());
        }
        $this->set('diensten', $diensten);

        return $izDeelnemer;
    }

    public function toon_matching($id)
    {
        $this->view = 'AppTwig';
        $em = $this->getEntityManager();

        $izDeelnemer = $em->find(IzDeelnemer::class, $id);
        $matching = $izDeelnemer->getMatching();
        $kandidaten = [];

        if ($izDeelnemer instanceof IzVrijwilliger) {
            $this->set('iz_vrijwilliger', $izDeelnemer);
            $kandidatenClass = IzKlant::class;
        } else {
            $this->set('iz_klant', $izDeelnemer);
            $kandidatenClass = IzVrijwilliger::class;
        }

        if ($izDeelnemer->getMatching()) {
            $kandidaten = $em->getRepository($kandidatenClass)->findMatching($matching);
        }

        // set view vars
        $this->getIzDeelnemer($id);
        $this->setMedewerkers();
        $this->setMetadata($id);
        $this->set('kandidaten', $kandidaten);

        $this->render('view');
    }

    public function matching($id)
    {
        $this->view = 'AppTwig';
        $em = $this->getEntityManager();

        $izDeelnemer = $em->find(IzDeelnemer::class, $id);
        $matching = $izDeelnemer->getMatching();

        if ($izDeelnemer instanceof IzVrijwilliger) {
            $this->set('iz_vrijwilliger', $izDeelnemer);
            $formClass = MatchingVrijwilligerType::class;
            if (null === $matching) {
                $matching = new MatchingVrijwilliger($izDeelnemer);
            }
        } else {
            $this->set('iz_klant', $izDeelnemer);
            $formClass = MatchingKlantType::class;
            if (null === $matching) {
                $matching = new MatchingKlant($izDeelnemer);
            }
        }

        $form = $this->createForm($formClass, $matching);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($matching);
            $em->flush();

            $this->redirect(['action' => 'toon_matching', $id]);
        }

        // set view vars
        $this->getIzDeelnemer($id);
        $this->setMedewerkers();
        $this->setMetadata($id);
        $this->set('form', $form->createView());

        $this->render('view');
    }

    public function verslagen($id)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->getIzDeelnemer($id);
        $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];
        $foreign_key = $iz_deelnemer['IzDeelnemer']['foreign_key'];

        $persoon = $this->{$persoon_model}->getAllById($foreign_key);
        $iz_deelnemer = $this->IzDeelnemer->getAllById($id);
        $iz_koppeling_ids = Set::ClassicExtract($iz_deelnemer, 'IzKoppeling.{n}.id');
        $iz_koppeling_id = $this->getParam('iz_koppeling_id');

        if (empty($iz_koppeling_id) && !empty($iz_koppeling_id) && !in_array($iz_koppeling_id, $iz_koppeling_ids)) {
            $this->Session->setFlash(__('ID bestaat niet', true));
            $this->redirect('/');
        }

        $iz_koppeling_gekoppeld = [];
        $iz_koppeling = $iz_deelnemer['IzKoppeling'][array_search($iz_koppeling_id, $iz_koppeling_ids)];
        $iz_koppeling_gekoppelde_id = $iz_koppeling['iz_koppeling_id'];

        $other_model = null;
        $other_id = null;

        $other_persoon = [];

        $conditions = [
            'IzVerslag.iz_koppeling_id' => $iz_koppeling_id,
        ];

        if (!empty($iz_koppeling_gekoppelde_id)) {
            $conditions = [
                'OR' => [
                   [
                    'IzVerslag.iz_koppeling_id' => $iz_koppeling_id,
                   ],
                   [
                    'IzVerslag.iz_koppeling_id' => $iz_koppeling_gekoppelde_id,
                   ],
                ],
            ];

            $iz_koppeling_gekoppeld = $this->IzDeelnemer->IzKoppeling->getAllById($iz_koppeling_gekoppelde_id, ['IzDeelnemer']);
            $other_model = $iz_koppeling_gekoppeld['IzDeelnemer']['model'];
            $other_id = $iz_koppeling_gekoppeld['IzDeelnemer']['foreign_key'];
            $other_persoon = [];
            if (!empty($other_id)) {
                if ('Klant' == $other_model) {
                    $other_persoon = $this->IzDeelnemer->Klant->getById($other_id);
                }
                if ('Vrijwilliger' == $other_model) {
                    $other_persoon = $this->IzDeelnemer->Vrijwilliger->getById($other_id);
                }
            }
        }

        $verslagen = $this->IzDeelnemer->IzVerslag->find('all', [
            'conditions' => $conditions,
            'contain' => [],
            'order' => 'created DESC',
        ]);

        $diensten = [];

        if ('Klant' == $persoon_model) {
            $diensten = $this->IzDeelnemer->Klant->diensten($foreign_key, $this->getEventDispatcher());
        }

        $this->set('diensten', $diensten);

        $this->set(compact('verslagen', 'persoon', 'persoon_model', 'id', 'iz_deelnemer', 'iz_koppeling', 'other_persoon'));
        $this->setMedewerkers();
        $this->setMetadata($id);
        $this->render('view');
    }

    public function koppeling_aanpassen($id = null)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->IzDeelnemer->getById($id);

        if (empty($iz_deelnemer['IzDeelnemer'])) {
            $this->Session->setFlash(__('ID bestaat niet (1)', true));
            $this->redirect('/');
        }

        $iz_koppeling_id = $this->getParam('iz_koppeling_id');

        if (empty($iz_koppeling_id)) {
            $this->Session->setFlash(__('ID bestaat niet (2)', true));
            $this->redirect('/');
        }

        $iz_koppeling = $this->IzDeelnemer->IzKoppeling->getById($iz_koppeling_id);

        if ($iz_koppeling['iz_deelnemer_id'] != $id) {
            $this->Session->setFlash(__('ID bestaat niet (3)', true));
            $this->redirect('/');
        }

        if (!empty($this->data)) {
            $this->data['IzKoppeling']['id'] = $iz_koppeling_id;
            $data2 = [];

            if (!empty($iz_koppeling['iz_koppeling_id'])) {
                $data2 = [
                    'IzKoppeling' => ['id' => $iz_koppeling['iz_koppeling_id']],
                ];

                if (!empty($this->data['IzKoppeling']['medewerker_id'])) {
                    $data2['IzKoppeling']['medewerker_id'] = $this->data['IzKoppeling']['medewerker_id'];
                }

                if (!empty($this->data['IzKoppeling']['koppeling_startdatum'])) {
                    $data2['IzKoppeling']['koppeling_startdatum'] = $this->data['IzKoppeling']['koppeling_startdatum'];
                }
            }

            $this->IzDeelnemer->begin();

            $saved = false;

            $this->IzDeelnemer->IzKoppeling->create();

            if ($this->IzDeelnemer->IzKoppeling->save($this->data)) {
                if (!empty($data2)) {
                    $this->IzDeelnemer->IzKoppeling->create();

                    if ($this->IzDeelnemer->IzKoppeling->save($data2)) {
                        $saved = true;
                    }
                } else {
                    $saved = true;
                }
            }

            if ($saved) {
                $this->IzDeelnemer->commit();
                $this->Session->setFlash(__('De koppeling is aangepast', true));
            } else {
                $this->IzDeelnemer->rollback();
                $this->Session->setFlash(__('De koppeling is niet aangepast', true));
            }
        }

        $iz_koppeling = $this->IzDeelnemer->IzKoppeling->getById($iz_koppeling_id);
        $this->redirect(['action' => 'koppelingen', $id]);
    }

    public function koppeling_openen($id = null)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->IzDeelnemer->getById($id);

        if (empty($iz_deelnemer['IzDeelnemer'])) {
            $this->Session->setFlash(__('ID bestaat niet (1)', true));
            $this->redirect('/');
        }

        $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];
        $iz_koppeling_id = $this->getParam('iz_koppeling_id');

        if (empty($iz_koppeling_id)) {
            $this->Session->setFlash(__('ID bestaat niet (2)', true));
            $this->redirect('/');
        }

        $iz_koppeling = $this->IzDeelnemer->IzKoppeling->getById($iz_koppeling_id);

        if ($iz_koppeling['iz_deelnemer_id'] != $id) {
            $this->Session->setFlash(__('ID bestaat niet (3)', true));
            $this->redirect('/');
        }

        $this->IzDeelnemer->IzKoppeling->create();

        if (!empty($iz_koppeling_id)) {
            $this->IzDeelnemer->begin();

            $data2 = [];

            $data = [
                'IzKoppeling' => [
                    'id' => $iz_koppeling_id,
                    'einddatum' => null,
                    'koppeling_einddatum' => null,
                    'iz_eindekoppeling_id' => null,
                    'iz_vraagaanbod_id' => null,
                    'koppeling_succesvol' => null,
                ],
            ];

            if (!empty($iz_koppeling['iz_koppeling_id'])) {
                $data2 = [
                    'IzKoppeling' => [
                        'id' => $iz_koppeling['iz_koppeling_id'],
                        'einddatum' => null,
                        'koppeling_einddatum' => null,
                        'iz_eindekoppeling_id' => null,
                        'iz_vraagaanbod_id' => null,
                        'koppeling_succesvol' => null,
                    ],
                ];
            }

            unset($this->IzDeelnemer->IzKoppeling->validate['iz_eindekoppeling_id']);
            unset($this->IzDeelnemer->IzKoppeling->validate['iz_vraagaanbod_id']);

            $saved = false;

            if ($this->IzDeelnemer->IzKoppeling->save($data)) {
                $saved = true;

                if (!empty($data2)) {
                    if (!$this->IzDeelnemer->IzKoppeling->save($data2)) {
                        $saved = false;
                    }
                }
            }

            if ($saved) {
                $this->Session->setFlash(__('De koppeling is vrijgegeven', true));
                $this->IzDeelnemer->commit();
            } else {
                $this->Session->setFlash(__('De koppeling is niet vrijgegeven'.$msg, true));
                $this->IzDeelnemer->rollback();
            }
        }

        $this->redirect(['action' => 'koppelingen', $id]);
    }

    public function koppeling_medewerker($id = null)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->IzDeelnemer->getById($id);

        if (empty($iz_deelnemer['IzDeelnemer'])) {
            $this->Session->setFlash(__('ID bestaat niet', true));
            $this->redirect('/');
        }

        $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];

        if (!empty($this->data)) {
            $this->data['IzKoppeling']['iz_deelnemer_id'] = $id;

            $this->IzDeelnemer->IzKoppeling->create();

            if ($this->IzDeelnemer->IzKoppeling->save($this->data)) {
                $this->Session->setFlash(__('De medewerker is opgeslagen', true));
            } else {
                $this->Session->setFlash(__('De medewerker is niet opgeslagen'.$msg, true));
            }
        }

        $this->redirect(['action' => 'koppelingen', $id]);
    }

    public function koppelingen($id = null)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->IzDeelnemer->getById($id);

        if (empty($iz_deelnemer['IzDeelnemer'])) {
            $this->Session->setFlash(__('ID bestaat niet', true));
            $this->redirect('/');
        }

        $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];
        $foreign_key = $iz_deelnemer['IzDeelnemer']['foreign_key'];

        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);
        $persoon = $this->{$persoon_model}->getAllById($foreign_key);

        if (!empty($this->data)) {
            $this->data['IzKoppeling']['iz_deelnemer_id'] = $id;

            $this->IzDeelnemer->begin();

            $saved = true;

            $this->IzDeelnemer->IzKoppeling->create();

            if ($this->data['IzDeelnemer']['form'] == 'koppel') {
                $iz_koppeling['IzKoppeling'] = $this->IzDeelnemer->IzKoppeling->getById($this->data['IzKoppeling']['iz_koppeling_id']);

                if ($this->data['IzKoppeling']['koppeling_startdatum']['day'] == 0 || $this->data['IzKoppeling']['koppeling_startdatum']['month'] == 0 || $this->data['IzKoppeling']['koppeling_startdatum']['year'] == 0) {
                    $this->data['IzKoppeling']['koppeling_startdatum'] = date('Y-m-d');
                }

                $this->data['IzKoppeling']['koppeling_einddatum'] = $iz_koppeling['IzKoppeling']['einddatum'];

                $data['IzKoppeling'] = [
                    'id' => $iz_koppeling['IzKoppeling']['id'],
                    'iz_deelnemer_id' => $iz_koppeling['IzKoppeling']['iz_deelnemer_id'],
                    'koppeling_startdatum' => $this->data['IzKoppeling']['koppeling_startdatum'],
                    'koppeling_einddatum' => $iz_koppeling['IzKoppeling']['einddatum'],
                ];

                $data['IzKoppeling']['iz_koppeling_id'] = $this->data['IzKoppeling']['id'];
            }

            if (!$this->IzDeelnemer->IzKoppeling->save($this->data)) {
                $saved = false;
            }

            if ($saved && $this->data['IzDeelnemer']['form'] == 'koppel') {
                $this->IzDeelnemer->IzKoppeling->create();
                if (!$this->IzDeelnemer->IzKoppeling->save($data)) {
                    $saved = false;
                }
            }

            if ($saved) {
                $this->IzDeelnemer->commit();
                $this->Session->setFlash(__('De koppeling is opgeslagen', true));
                $this->redirect(['controller' => 'iz_deelnemers', 'action' => 'koppelingen', $this->data['IzDeelnemer']['id']]);
            } else {
                $msg = '';

                foreach ($this->IzDeelnemer->IzKoppeling->validationErrors as $e) {
                    $msg .= " {$e}";
                }

                $this->IzDeelnemer->rollback();
                $this->Session->setFlash(__('De koppeling is niet opgeslagen'.$msg, true));
            }
        }

        $koppelingen = $this->IzDeelnemer->IzKoppeling->find('all', [
            'conditions' => [
                'iz_deelnemer_id' => $id,
             ],
        ]);

        $usedprojects = [];
        $requestedprojects = [];
        $activeprojects = [];

        $now = strtotime(date('Y-m-d'));

        foreach ($koppelingen as $key => $koppeling) {
            $koppelingen[$key]['IzKoppeling']['section'] = 0;

            if (!empty($koppeling['IzKoppeling']['einddatum']) && strtotime($koppeling['IzKoppeling']['einddatum']) <= $now) {
                $koppelingen[$key]['IzKoppeling']['section'] = 1;
            }

            if (!empty($koppeling['IzKoppeling']['iz_koppeling_id'])) {
                $koppelingen[$key]['IzKoppeling']['section'] = 2;

                $k = $this->IzDeelnemer->IzKoppeling->getAllById($koppeling['IzKoppeling']['iz_koppeling_id']);
                if ($k) {
                    $p = $this->IzDeelnemer->{$k['IzDeelnemer']['model']}->getById($k['IzDeelnemer']['foreign_key']);
                    $koppelingen[$key]['IzKoppeling']['name_of_koppeling'] = $p['name'];
                    $koppelingen[$key]['IzKoppeling']['iz_deelnemer_id_of_other'] = $k['IzKoppeling']['iz_deelnemer_id'];
                    $koppelingen[$key]['IzKoppeling']['koppeling_of_other'] = $koppeling['IzKoppeling']['iz_koppeling_id'];
                    $koppelingen[$key]['IzKoppeling']['foreing_key_of_other'] = $p['id'];
                    $koppelingen[$key]['IzKoppeling']['model_of_other'] = $k['IzDeelnemer']['model'];
                }
            }

            if (!empty($koppeling['IzKoppeling']['koppeling_einddatum']) && strtotime($koppeling['IzKoppeling']['koppeling_einddatum']) <= $now) {
                $koppelingen[$key]['IzKoppeling']['section'] = 3;
            }

            if ($koppelingen[$key]['IzKoppeling']['section'] == 0 || $koppelingen[$key]['IzKoppeling']['section'] == 2) {
                $usedprojects[] = $koppelingen[$key]['IzKoppeling']['project_id'];
            }

            if ($koppelingen[$key]['IzKoppeling']['section'] == 0) {
                $requestedprojects[] = $koppelingen[$key]['IzKoppeling']['project_id'];
            }
        }

        $this->set(compact('persoon', 'persoon_model'));
        $this->setMedewerkers();

        $activeprojects = $this->IzDeelnemer->IzDeelnemersIzProject->IzProject->projectLists(false);
        $projects = $this->IzDeelnemer->IzDeelnemersIzProject->IzProject->projectLists(true);
        $selectableprojects = [];

        foreach ($activeprojects as $key => $project) {
            if ('' == $key) {
                $selectableprojects[''] = '';
                continue;
            }

            if (!in_array($key, $usedprojects)) {
                $selectableprojects[$key] = $project;
                continue;
            }
        }

        $candidates = $this->IzDeelnemer->IzKoppeling->getCandidatesForProjects($persoon_model, $requestedprojects);

        foreach ($koppelingen as $key => $koppeling) {
            if ($koppelingen[$key]['IzKoppeling']['section'] != 0) {
                $koppelingen[$key]['iz_koppeling_id'] = [];
                continue;
            }

            $koppelingen[$key]['iz_koppeling_id'] = $candidates[$koppeling['IzKoppeling']['project_id']];
        }

        $iz_eindekoppelingen = $this->IzDeelnemer->IzKoppeling->IzEindekoppeling->eindekoppelingList(true);
        $iz_eindekoppelingen_active = $this->IzDeelnemer->IzKoppeling->IzEindekoppeling->eindekoppelingList(false);
        $iz_vraagaanboden = $this->IzDeelnemer->IzKoppeling->IzVraagaanbod->vraagaanbodList();

        $activeprojects = ['' => ''] + $activeprojects;
        $diensten = [];

        if ('Klant' == $persoon_model) {
            $diensten = $this->IzDeelnemer->Klant->diensten($foreign_key, $this->getEventDispatcher());
        }

        $this->set('diensten', $diensten);

        $this->set(compact('persoon', 'persoon_model', 'id', 'iz_deelnemer', 'projects', 'koppelingen',
            'iz_afsluitingen', 'selectableprojects', 'activeprojects', 'iz_eindekoppelingen', 'iz_vraagaanboden',
            'iz_eindekoppelingen_active'
        ));

        $this->setMetadata($id);
        $this->render('view');
    }

    public function koppelingen_vraag_aanbod_afsluiten($id)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->IzDeelnemer->getById($id);

        if (empty($iz_deelnemer['IzDeelnemer'])) {
            $this->Session->setFlash(__('ID bestaat niet', true));
            $this->redirect('/');
        }

        $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];
        $foreign_key = $iz_deelnemer['IzDeelnemer']['foreign_key'];

        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);

        $persoon = $this->{$persoon_model}->getAllById($foreign_key);
        $this->data['IzKoppeling']['einddatum'] = date('Y-m-d');

        if (!empty($this->data)) {
            $this->data['IzKoppeling']['iz_deelnemer_id'] = $id;

            if ($this->IzDeelnemer->IzKoppeling->save($this->data)) {
                $this->data['result'] = true;
            }

            $key = $this->data['IzKoppeling']['key'];
        } else {
            $this->redirect('/');
        }

        $iz_vraagaanboden = $this->IzDeelnemer->IzKoppeling->IzVraagaanbod->vraagaanbodList();

        $this->set(compact('id', 'key', 'iz_vraagaanboden'));

        if ($this->RequestHandler->isAjax()) {
            $this->render('koppelingen_vraag_aanbod_afsluiten', 'ajax');
        } else {
            $this->render('koppelingen_vraag_aanbod_afsluiten');
        }
    }

    public function koppelingen_afsluiten($id)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->IzDeelnemer->getById($id);

        if (empty($iz_deelnemer['IzDeelnemer'])) {
            $this->Session->setFlash(__('ID bestaat niet', true));
            $this->redirect('/');
        }

        $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];
        $foreign_key = $iz_deelnemer['IzDeelnemer']['foreign_key'];

        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);
        $persoon = $this->{$persoon_model}->getAllById($foreign_key);

        if (!empty($this->data)) {
            $iz_koppeling['IzKoppeling'] = $this->IzDeelnemer->IzKoppeling->getById($this->data['IzKoppeling']['id']);

            $data['IzKoppeling'] = [
                    'id' => $iz_koppeling['IzKoppeling']['iz_koppeling_id'],
                    'koppeling_startdatum' => $this->data['IzKoppeling']['koppeling_startdatum'],
                    'koppeling_einddatum' => $this->data['IzKoppeling']['koppeling_einddatum'],
                    'iz_eindekoppeling_id' => $this->data['IzKoppeling']['iz_eindekoppeling_id'],
                    'koppeling_succesvol' => $this->data['IzKoppeling']['koppeling_succesvol'],
            ];

            $saved = true;

            $this->IzDeelnemer->begin();

            $this->IzDeelnemer->IzKoppeling->create();

            if (!$this->IzDeelnemer->IzKoppeling->save($this->data)) {
                $saved = false;
            }
            $this->IzDeelnemer->IzKoppeling->create();
            if (!$this->IzDeelnemer->IzKoppeling->save($data)) {
                $saved = false;
            }

            if ($saved) {
                $this->data['result'] = true;
                $this->IzDeelnemer->commit();
            } else {
                $this->IzDeelnemer->rollback();
            }

            $key = $this->data['IzKoppeling']['key'];
        } else {
            $this->redirect('/');
        }

        $iz_eindekoppelingen = $this->IzDeelnemer->IzKoppeling->IzEindekoppeling->eindekoppelingList();
        $this->set(compact('id', 'key', 'iz_eindekoppelingen'));

        if ($this->RequestHandler->isAjax()) {
            $this->render('koppelingen_afsluiten', 'ajax');
        } else {
            $this->render('koppelingen_afsluiten');
        }
    }

    public function opnieuw_aanmelden($id)
    {
        $data = [
            'IzDeelnemer' => [
                'id' => $id,
                'datumafsluiting' => null,
                'iz_afsluiting_id' => null,
            ],
        ];

        $iz_deelnemer = $this->IzDeelnemer->getById($id);

        if (empty($iz_deelnemer)) {
            $this->redirect('/');
        }

        $this->IzDeelnemer->validate = [];

        if (!$this->IzDeelnemer->save($data)) {
            debug($this->IzDeelnemer->validationErrors);
            die;
        }

        $this->redirect(['action' => 'view', $iz_deelnemer['model'], $iz_deelnemer['foreign_key'], $id]);
    }

    public function afsluiting($id)
    {
        $this->loadModel('GroepsactiviteitenGroepenKlant');

        if (empty($id)) {
            $this->Session->setFlash(__('ID bestaat niet', true));
            $this->redirect('/');
        }

        if (!empty($this->data)) {
            $this->data['IzDeelnemer']['id'] = $id;

            $data = [
                'IzDeelnemer' => [
                        'id' => $id,
                        'iz_afsluiting_id' => $this->data['IzDeelnemer']['iz_afsluiting_id'],
                        'datumafsluiting' => $this->data['IzDeelnemer']['datumafsluiting'],
                ],
            ];

            if ($this->IzDeelnemer->save($data)) {
                $this->Session->setFlash(__('Het IZ-dossier is afgesloten', true));
                $this->redirect(['action' => 'afsluiting', $id]);
            } else {
                $this->Session->setFlash(__('Het IZ-dossier kan niet worden afgesloten.', true));
            }
        } else {
            $this->data = $this->IzDeelnemer->getAllById($id);
        }

        $conditions = [
                'klant_id' => $this->data['IzDeelnemer']['foreign_key'],
                'groepsactiviteiten_groep_id' => 19,
                'einddatum' => null,
        ];

        $ga = $this->GroepsactiviteitenGroepenKlant->find('first', [
            'conditions' => $conditions,
            'contain' => [],
        ]);

        $eropuit = false;
        if (!empty($ga)) {
            $eropuit = true;
        }

        $this->set('eropuit', $eropuit);

        $iz_afsluitingen = $this->IzDeelnemer->IzAfsluiting->afsluitingList(true);
        $iz_afsluitingen_active = $this->IzDeelnemer->IzAfsluiting->afsluitingList(false);

        $has_active_koppelingen = $this->IzDeelnemer->hasActiveKoppelingen($id);

        $diensten = [];

        if ($this->data['IzDeelnemer']['model'] == 'Klant') {
            $diensten = $this->IzDeelnemer->Klant->diensten($this->data['IzDeelnemer']['foreign_key'], $this->getEventDispatcher());
        }

        $this->set('diensten', $diensten);

        $this->set(compact('has_active_koppelingen', 'iz_afsluitingen', 'iz_afsluitingen_active', 'eropouit'));
        $this->setMetadata($id);

        $this->render('view');
    }

    public function vrijwilliger_intervisiegroepen($id)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->IzDeelnemer->getById($id);

        if (empty($iz_deelnemer['IzDeelnemer'])) {
            $this->Session->setFlash(__('ID bestaat niet', true));
            $this->redirect('/');
        }

        if (!empty($this->data)) {
            if (!empty($this->data['IzDeelnemer']['iz_intervisiegroep_id'])) {
                foreach ($this->data['IzDeelnemer']['iz_intervisiegroep_id'] as $iz_intervisiegroep_id) {
                    $tmp = ['iz_intervisiegroep_id' => $iz_intervisiegroep_id];

                    if (!empty($id)) {
                        $tmp['iz_deelnemer_id'] = $id;
                    }

                    $this->data['IzDeelnemersIzIntervisiegroep'][] = $tmp;
                }
            }

            $saved = false;

            $this->IzDeelnemer->begin();

            $this->IzDeelnemer->IzDeelnemersIzIntervisiegroep->create();

            if (!empty($id)) {
                if (!$this->IzDeelnemer->IzDeelnemersIzIntervisiegroep->deleteAll(['iz_deelnemer_id' => $id])) {
                    break;
                }
            }

            if (isset($this->data['IzDeelnemersIzIntervisiegroep'])) {
                $retval = $this->IzDeelnemer->IzDeelnemersIzIntervisiegroep->saveAll($this->data['IzDeelnemersIzIntervisiegroep'], ['atomic' => false]);

                if (is_saved($retval)) {
                    $saved = true;
                }
            } else {
                $saved = true;
            }

            if ($saved) {
                $this->IzDeelnemer->commit();
                $this->Session->setFlash(__('De intervisiegroepen zijn opgeslagen.', true));
            } else {
                $this->IzDeelnemer->rollback();
                $this->Session->setFlash(__('De intervisiegroepen kunnen niet worden opgeslagen.', true));
            }
        } else {
            $this->data = $this->IzDeelnemer->getAllById($id);
            $this->data['IzDeelnemer']['iz_intervisiegroep_id'] = Set::ClassicExtract($this->data, 'IzDeelnemersIzIntervisiegroep.{n}.iz_intervisiegroep_id');
        }

        $intervisiegroepenlists = $this->IzDeelnemer->IzDeelnemersIzIntervisiegroep->IzIntervisiegroep->intervisiegroepenLists();
        $diensten = [];
        $this->set('diensten', $diensten);

        $this->set(compact('intervisiegroepenlists'));
        $this->setMetadata($id);

        $this->render('view');
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid iz deelnemer', true));
            $this->redirect(['action' => 'index']);
        }

        if (!empty($this->data)) {
            if ($this->IzDeelnemer->save($this->data)) {
                $this->Session->setFlash(__('The iz deelnemer has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash(__('The iz deelnemer could not be saved. Please, try again.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->IzDeelnemer->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for iz deelnemer', true));
            $this->redirect(['action' => 'index']);
        }

        if ($this->IzDeelnemer->delete($id)) {
            $this->Session->setFlash(__('Iz deelnemer deleted', true));
            $this->redirect(['action' => 'index']);
        }

        $this->Session->setFlash(__('Iz deelnemer was not deleted', true));

        $this->redirect(['action' => 'index']);
    }

    public function email_selectie()
    {
        $form = $this->createForm(IzEmailMessageType::class, null);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Swift_Mailer $mailer */
            $mailer = $this->container->get('mailer');

            /** @var Swift_Mime_Message $message */
            $message = $mailer->createMessage()
                ->setFrom($form->get('from')->getData())
                ->setTo(explode(', ', $form->get('to')->getData()))
                ->setSubject($form->get('subject')->getData())
                ->setBody($form->get('text')->getData(), 'text/plain')
            ;

            // add attachments
            if ($form->get('file1')->getData()) {
                $message->attach(\Swift_Attachment::fromPath($form->get('file1')->getData()->getPathName()));
            }
            if ($form->get('file2')->getData()) {
                $message->attach(\Swift_Attachment::fromPath($form->get('file2')->getData()->getPathName()));
            }
            if ($form->get('file3')->getData()) {
                $message->attach(\Swift_Attachment::fromPath($form->get('file3')->getData()->getPathName()));
            }

            if ($mailer->send($message)) {
                $this->flash(__('Email is succesvol verzonden', true));
            } else {
                $this->flashError(__('Email kon niet worden verzonden', true));
            }

            return $this->redirect(['action' => 'selecties']);
        }

        $this->loadModel('QueueTask');

        if (empty($this->data)) {
            $this->redirect(['action' => 'selecties']);
        }

        if (!empty($this->data)) {
            foreach ($this->data['Document'] as $key => $v) {
                if ($v['file']['error'] != 0) {
                    unset($this->data['Document'][$key]);
                    continue;
                }

                $this->data['Document'][$key]['title'] = $v['file']['name'];
                $this->data['Document'][$key]['model'] = 'QueueTask';
            }

            if (empty($this->data['IzDeelnemer']['onderwerp'])) {
                $this->IzDeelnemer->invalidate('onderwerp', 'Email moet een onderwerp hebben');
            }

            if (empty($this->data['IzDeelnemer']['text'])) {
                $this->IzDeelnemer->invalidate('text', 'Email moet een inhoud hebben');
            }

            $function = 'getPersonen';

            if ($this->data['IzDeelnemer']['emailsource'] == 'intervisiegroepen') {
                $function = 'getIntervisiePersonen';
            }

            $this->data['QueueTask'] = [
                'model' => 'Medewerker',
                'foreign_key' => $this->Session->read('Auth.Medewerker.id'),
                'data' => [
                    'selectie' => $this->data,
                    'email' => $this->data,
                    'model' => 'IzDeelnemer',
                    'function' => $function,
                ],
                'action' => 'mass_email',
                'status' => STATUS_PENDING,
            ];

            if (empty($this->IzDeelnemer->validationErrors)) {
                $ret = $this->QueueTask->saveAll($this->data);

                $saved = $ret;

                if (!empty($this->QueueTask->Document->validationErrors)) {
                    $saved = false;
                }

                if ($saved) {
                    $this->flash(__('Email is opgeslagen en zal z.s.m. verzonden worden', true));
                    $this->redirect(['action' => 'selecties']);

                    return;
                } else {
                    $this->flashError(__('Email kan niet verwerkt worden', true));
                }
            }
        }

        $personen = $this->IzDeelnemer->getPersonen($this->data, $only_email = true);

        $this->set(compact('personen'));
    }

    public function formatPostedDate($key, $default = null)
    {
        $date = $this->data[$key];

        if (!empty($date['year']) && !empty($date['month']) && !empty($date['day'])) {
            $date = $date['year'].'-'.$date['month'].'-'.$date['day'];
        } else {
            $date = $default;
        }

        return $date;
    }

    public function selecties()
    {
        ini_set('memory_limit', '512M');

        // render with Twig
        $this->view = 'AppTwig';

        $form = $this->createForm(IzDeelnemerSelectieType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $izKlanten = new ArrayCollection([]);
            $izVrijwilligers = new ArrayCollection([]);

            if (in_array('klanten', $form->getData()->personen)) {
                $repository = $this->getEntityManager()->getRepository(IzKlant::class);
                $builder = $repository->createQueryBuilder('izKlant')
                    ->select('izKlant, klant, geslacht, izIntake, medewerkerIzIntake, izHulpvraag, medewerkerIzHulpvraag, project')
                    ->innerJoin('izKlant.klant', 'klant')
                    ->leftJoin('klant.geslacht', 'geslacht')
                    ->leftJoin('izKlant.izIntake', 'izIntake')
                    ->leftJoin('izIntake.medewerker', 'medewerkerIzIntake')
                    ->leftJoin('izKlant.izHulpvragen', 'izHulpvraag')
                    ->leftJoin('izHulpvraag.medewerker', 'medewerkerIzHulpvraag')
                    ->leftJoin('izHulpvraag.project', 'project')
                ;
                $form->getData()->applyTo($builder);
                $izKlanten = $builder->getQuery()->getResult();
            }

            if (in_array('vrijwilligers', $form->getData()->personen)) {
                $repository = $this->getEntityManager()->getRepository(IzVrijwilliger::class);
                $builder = $repository->createQueryBuilder('izVrijwilliger')
                    ->select('izVrijwilliger, vrijwilliger, geslacht, izIntake, medewerkerIzIntake, izHulpaanbod, medewerkerIzHulpaanbod, project')
                    ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
                    ->leftJoin('vrijwilliger.geslacht', 'geslacht')
                    ->leftJoin('izVrijwilliger.izIntake', 'izIntake')
                    ->leftJoin('izIntake.medewerker', 'medewerkerIzIntake')
                    ->leftJoin('izVrijwilliger.izHulpaanbiedingen', 'izHulpaanbod')
                    ->leftJoin('izHulpaanbod.medewerker', 'medewerkerIzHulpaanbod')
                    ->leftJoin('izHulpaanbod.project', 'project')
                ;
                $form->getData()->applyTo($builder);
                $izVrijwilligers = $builder->getQuery()->getResult();
            }

            if (count($izKlanten) + count($izVrijwilligers) > 0) {
                $this->set('izKlanten', $izKlanten);
                $this->set('izVrijwilligers', $izVrijwilligers);

                switch ($form->getData()->formaat) {
                    case 'etiketten':
                        $data = [];
                        foreach ($izKlanten as $izKlant) {
                            $klant = $izKlant->getKlant();
                            $data[] = [
                                '﻿"naam volledig"' => (string) $klant,
                                'straat' => $klant->getAdres(),
                                'huisnummer' => '',
                                'huisnummer_toev' => '',
                                'postcode met spatie' => $klant->getPostcode(),
                                'woonplaats' => $klant->getPlaats(),
                            ];
                        }
                        foreach ($izVrijwilligers as $izVrijwilliger) {
                            $vrijwilliger = $izVrijwilliger->getVrijwilliger();
                            $data[] = [
                                '﻿"naam volledig"' => (string) $vrijwilliger,
                                'straat' => $vrijwilliger->getAdres(),
                                'huisnummer' => '',
                                'huisnummer_toev' => '',
                                'postcode met spatie' => $vrijwilliger->getPostcode(),
                                'woonplaats' => $vrijwilliger->getPlaats(),
                            ];
                        }

                        // @todo improve this
                        if (!$this->IzDeelnemer->setOdsEtikettenTemplate()) {
                            debug($this->IzDeelnemer->getOdsEtikettenErrors());
                            die;
                        }

                        $this->IzDeelnemer->setOdsEtikettenData($data);
                        $this->IzDeelnemer->saveOdsEtikettenData();

                        $extension = 'odt';
                        $fileName = $this->IzDeelnemer->getOdsEtikettenFilename($extension);

                        /* @see http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Views.html#media-views */
                        $this->view = 'Media';
                        $this->set([
                            'id' => basename($fileName),
                            'name' => sprintf('%s_%s', date('U'), basename($fileName, ".{$extension}")),
                            'extension' => $extension,
                            'mimeType' => [$extension => 'application/vnd.ms-word'],
                            'path' => preg_replace(sprintf('/%s$/', basename($fileName)), '', $fileName),
                            'cache' => false,
                        ]);

                        return $this->render();

                    case 'email':
                        // convert IzKlant and IzVrijwilliger collections to IzDeelnemer collection
                        $izDeelnemers = $this->getEntityManager()->getRepository(IzDeelnemer::class)
                            ->createQueryBuilder('izDeelnemer')
                            ->where('izDeelnemer IN (:iz_klanten)')
                            ->orWhere('izDeelnemer IN (:iz_vrijwilligers)')
                            ->setParameter('iz_klanten', $izKlanten)
                            ->setParameter('iz_vrijwilligers', $izVrijwilligers)
                            ->getQuery()
                            ->getResult();

                        $form = $this->createForm(IzEmailMessageType::class, null, [
                            'from' => $this->Session->read('Auth.Medewerker.LdapUser.mail'),
                            'to' => $izDeelnemers,
                        ]);
                        $this->set('form', $form->createView());

                        return $this->render('email_selectie');

                    default:
                    case 'excel':
                        $this->autoRender = false;
                        $filename = sprintf('selecties_%s.xlsx', date('Ymd_His'));

                        $export = $this->container->get('iz.export.selectie');
                        $export->create($izKlanten)->create($izVrijwilligers)->send($filename);

                        return;
                }
            } else {
                $this->Session->setFlash('Geen personen gevonden');
            }
        }

        $this->set('form', $form->createView());
    }

    public function intervisiegroepen()
    {
        $personen = [];
        $projectlists = $this->IzDeelnemer->IzDeelnemersIzProject->IzProject->projectLists();
        $intervisiegroepenlists = $this->IzDeelnemer->IzDeelnemersIzIntervisiegroep->IzIntervisiegroep->intervisiegroepenLists();

        if (!empty($this->data)) {
            $validated = true;
            $msg = '';

            if ($validated) {
                $personen = $this->IzDeelnemer->getIntervisiePersonen($this->data);

                if ($this->data['IzDeelnemer']['export'] == 'csv') {
                    $date = date('Ymd_His');
                    $file = "selecties_{$date}.xls";

                    header('Content-type: application/vnd.ms-excel');
                    header("Content-Disposition: attachment; filename=\"$file\";");
                    header('Content-Transfer-Encoding: binary');
                    $this->autoLayout = false;
                    $this->layout = false;

                    $this->set('personen', $personen);
                    $this->render('selecties_excel');
                } elseif ($this->data['IzDeelnemer']['export'] == 'etiket') {
                    $data = [];

                    foreach ($personen as $persoon) {
                        $data[] = [
                            '﻿"naam volledig"' => $persoon['name'],
                            'straat' => $persoon['adres'],
                            'huisnummer' => '',
                            'huisnummer_toev' => '',
                            'postcode met spatie' => $persoon['postcode'],
                            'woonplaats' => $persoon['plaats'],
                        ];
                    }

                    if (!$this->IzDeelnemer->setOdsEtikettenTemplate()) {
                        debug($this->IzDeelnemer->getOdsEtikettenErrors());
                        die;
                    }

                    $this->IzDeelnemer->setOdsEtikettenData($data);
                    $this->IzDeelnemer->saveOdsEtikettenData();

                    $fileName = $this->IzDeelnemer->getOdsEtikettenFilename('odt');
                    if (!file_exists($fileName)) {
                        debug("{$fileName} does not exist");
                        die;
                    }

                    $f = fopen($fileName, 'r');
                    $this->log($fileName);

                    $name = date('U').'_etiketten.odt';

                    header('Content-type: application/vnd.ms-word');
                    header("Content-Disposition: attachment; filename=\"{$name}\";");
                    header('Content-Transfer-Encoding: binary');
                    fpassthru($f);

                    exit();
                } else {
                    $emailsource = 'intervisiegroepen';
                    $this->set(compact('emailsource', 'intervisiegroepenlists', 'personen', 'projectlists', 'intervisiegroepenlists'));
                    $this->render('email_selectie');
                }

                $personen = Set::sort($personen, '{n}.achternaam', 'asc');
            } else {
                $this->Session->setFlash(__("Selecteer voldoende opties ({$msg})", true));
            }
        }

        $diensten = [];

        $this->set(compact('intervisiegroepenlists', 'personen', 'projectlists', 'intervisiegroepenlists'));
    }

    public function beheer()
    {
    }

    public function delete_document($attachmentId)
    {
        $att = $this->IzDeelnemer->IzDeelnemerDocument->read(null, $attachmentId);

        if (!$att) {
            $this->flashError(__('Invalid attachment id.', true));
            $this->redirect('/');

            return;
        }

        if ($att['Attachment']['user_id'] != $this->Session->read('Auth.Medewerker.id')) {
            $this->flashError(__('Cannot delete attachment, you are not the uploader.', true));
            $this->redirect('/');

            return;
        }

        $this->Attachment->set('is_active', false);
        $this->Attachment->IzDeelnemer->IzDeelnemerDocument();

        $this->flash(__('Document deleted.', true));
        $model = $att['Attachment']['model'];

        $controller = Inflector::Pluralize($model);

        $this->redirect($this->referer());
    }

    public function upload($id)
    {
        $iz_deelnemer = $this->IzDeelnemer->getById($id);

        if (empty($iz_deelnemer)) {
            $this->redirect('/');
        }

        if (!empty($this->data)) {
            $this->data['IzDeelnemerDocument']['foreign_key'] = $id;
            $this->data['IzDeelnemerDocument']['model'] = 'IzDeelnemer';
            $this->data['IzDeelnemerDocument']['group'] = 'IzDeelnemer';
            $this->data['IzDeelnemerDocument']['user_id'] = $this->Session->read('Auth.Medewerker.id');

            if (empty($this->data['IzDeelnemerDocument']['title'])) {
                $this->data['IzDeelnemerDocument']['title'] = $this->data['IzDeelnemerDocument']['file']['name'];
            }

            if ($this->IzDeelnemer->IzDeelnemerDocument->save($this->data['IzDeelnemerDocument'])) {
                $this->Session->setFlash(__('Het document is opgeslagen.', true));

                $url = [
                        'controller' => 'iz_deelnemers',
                        'action' => 'view',
                        $iz_deelnemer['model'],
                        $iz_deelnemer['foreign_key'],
                        $id,
                ];

                $this->redirect($url);
            } else {
                $this->flashError(__('Het document kan niet worden opgeslagen', true));
            }
        }

        $this->set('id', $id);
    }
}
