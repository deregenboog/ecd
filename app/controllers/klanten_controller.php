<?php

use AppBundle\Entity\Klant;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\DossierStatus;

class KlantenController extends AppController
{
    public $name = 'Klanten';

    public $components = ['Filter', 'RequestHandler', 'Session'];

    const SESSION_KEY_PRINT_LETTER_KLANT_ID = 'SESSION_KEY_PRINT_LETTER_KLANT_ID';

    public function _isControllerAuthorized($controller)
    {
        $auth = parent::_isControllerAuthorized($controller);

        if ($auth
            && 'merge' == $this->action || 'findDuplicates' == $this->action
        ) {
            $auth = isset($this->userGroups[GROUP_TEAMLEIDERS]);
        }

        return $auth;
    }

    public function index()
    {
        if (isset($this->params['named'])) {
            if (isset($this->params['named']['rowUrl'])) {
                $urlArray = explode('.', $this->params['named']['rowUrl']);
                if (2 == count($urlArray)) {
                    $this->set('rowOnclickUrl', [
                        'controller' => $urlArray[0],
                        'action' => $urlArray[1],
                    ]);
                }
            }
            if (isset($this->params['named']['showDisabled'])) {
                $this->Klant->showDisabled = $this->params['named']['showDisabled'];
            }
        }

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
                    'fields' => ['datum_intake', 'id'],
                ],
                'Geslacht',
            ],
        ];

        $klanten = $this->paginate(null, $this->Filter->filterData);
        $klanten = $this->Klant->LasteIntake->completeKlantenIntakesWithLocationNames($klanten);
        $this->set(compact('klanten'));

        if ($this->RequestHandler->isAjax()) {
            $this->render('/elements/klantenlijst', 'ajax');
        }
    }

    public function update_last_intakes()
    {
        $this->Klant->update_last_intakes();
        $this->index();
        $this->render('index');
    }

    public function view($id = null)
    {
        $klant = $this->Klant->find('first', ['conditions' => ['Klant.id' => $id]]);
        if (!$klant) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(['action' => 'index']);
        }

        $status = $this->getEntityManager()->getRepository(DossierStatus::class)->findCurrentByKlantId($id);
        $this->set(compact('status'));

        $registraties = $this->Klant->Registratie->find('all', [
            'conditions' => ['Registratie.klant_id' => $klant['Klant']['id']],
            'order' => 'binnen desc',
            'limit' => 3,
        ]);

        $opmerkingen = $this->Klant->Opmerking->find('all', [
            'conditions' => ['Klant.id' => $id, 'Opmerking.gezien' => false],
            'contain' => $this->Klant->Opmerking->contain,
            'limit' => 10,
        ]);

        $this->set(compact('klant', 'registraties', 'opmerkingen'));

        if (isset($klant['Intake'][0])) {
            $newestIntake = $this->Klant->Intake->read(null, $klant['Intake'][0]['id']);
            $this->set('newestintake', $newestIntake);

            // get ZRM associated with intake
            $this->loadModel(ZrmReport::class);
            foreach (ZrmReport::getZrmReportModels() as $zrmReportModel) {
                $this->loadModel($zrmReportModel);
                $zrmReport = $this->{$zrmReportModel}->get_zrm_report('Intake', $newestIntake['Intake']['id']);
                if ($zrmReport) {
                    $this->set('zrmReport', $zrmReport);
                    $this->set('zrmData', $this->{$zrmReportModel}->zrm_data());
                    break;
                }
            }
        }

        $this->set('diensten', $this->Klant->diensten($id, $this->getEventDispatcher()));
    }

    public function registratie($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(['action' => 'index']);
        }

        $klant = $this->Klant->read(null, $id);
        $this->set('klant', $klant);

        $registraties = $this->Klant->Registratie->find('all', [
            'conditions' => ['Registratie.klant_id' => $klant['Klant']['id']],
            'limit' => 3, ]);
        $this->set('registraties', $registraties);

        if (isset($klant['Intake'][0])) {
            $newestintake = $this->Klant->Intake->read(null, $klant['Intake'][0]['id']);
            $this->set('newestintake', $newestintake);
        }

        $registraties = $this->Klant->Registratie->find('all', [
            'conditions' => ['Registratie.klant_id' => $id], ]);
        $this->set('registraties', $registraties);

        $this->set('klant_id', $id);
        $this->set('diensten', $this->Klant->diensten($id, $this->getEventDispatcher()));
    }

    public function zrm_add($id)
    {
        $this->loadModel(ZrmReport::class);
        $zrmReportModel = ZrmReport::getZrmReportModel();
        $this->loadModel($zrmReportModel);

        if (!empty($this->data)) {
            $this->{$zrmReportModel}->update_zrm_data_for_edit($this->data, 'Klant', $id, $id);
            if ($this->{$zrmReportModel}->save($this->data)) {
                $this->flash(__('ZRM opgeslagen', true));
                $this->redirect($this->data['Klant']['referer']);
            } else {
                $this->flashError(__('ZRM niet opgeslagen. Probeer het opnieuw', true));
            }
        } else {
            $this->data['Klant']['referer'] = $this->referer();
        }

        $this->set('id', $id);
        $this->set('model', $zrmReportModel);
        $this->set('zrmData', $this->{$zrmReportModel}->zrm_data());
        $this->set('zrmReportModel', $zrmReportModel);
    }

    public function zrm($id)
    {
        $klant = $this->Klant->read(null, $id);
        if (!$klant) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(['action' => 'index']);
        }

        $zrmReports = [];
        $zrmData = [];

        $this->loadModel(ZrmReport::class);
        foreach (ZrmReport::getZrmReportModels() as $zrmReportModel) {
            $this->loadModel($zrmReportModel);
            $zrmReports[$zrmReportModel] = $this->{$zrmReportModel}->find('all', [
                'conditions' => ['klant_id' => $id],
                'order' => $zrmReportModel.'.created DESC',
            ]);
            $zrmData[$zrmReportModel] = $this->{$zrmReportModel}->zrm_data();
        }

        $this->set('klant_id', $id);
        $this->set('klant', $klant);
        $this->set('referer', $this->referer());
        $this->set('diensten', $this->Klant->diensten($id, $this->getEventDispatcher()));
        $this->set('zrmReports', $zrmReports);
        $this->set('zrmData', $zrmData);
    }

    public function add($step = 1)
    {
        $steptmp = $this->getParam('step');
        if (!empty($steptmp)) {
            $step = $steptmp;
        }

        $generic = $this->getParam('generic');
        if (!empty($generic)) {
            $generic = true;
        } else {
            $generic = false;
        }

        $dups = [];
        if (!empty($this->data)) {
            switch ($step) {
                case 1:

                    $dups = $this->Klant->findDuplicates($this->data);
                    if (empty($dups)) {
                        $step = 3;
                    } else {
                        $this->Session->write('klant.add', $this->data);
                        $step = 2;
                    }

                    break;

                case 4:
                    $this->Klant->create();
                    $this->Klant->begin();
                    if ($this->Klant->save($this->data)) {
                        $this->Klant->commit();

                        // send CRM update email
                        if (!empty($this->Klant->send_admin_email)) {
                            $this->crmUpdate($this->Klant->id);
                        }

                        $this->flash(__('The klant has been saved', true));

                        $referer = ['action' => 'index'];
                        if (empty($generic) && $this->Klant->goesToInfobalie($this->data)) {
                            $referer = ['action' => 'printLetter', $this->Klant->id];
                        } else {
                            if (!empty($this->data['Klant']['referer'])) {
                                $referer = $this->data['Klant']['referer'];
                                if (preg_match('/IzDeelnemers/', $this->data['Klant']['referer'])) {
                                    $referer = ['controller' => 'iz_deelnemers', 'action' => 'aanmelding', 'Klant', $this->Klant->id];
                                }
                                if (preg_match('/iz_deelnemers/', $this->data['Klant']['referer'])) {
                                    $referer = ['controller' => 'iz_deelnemers', 'action' => 'aanmelding', 'Klant', $this->Klant->id];
                                }
                            }
                        }
                        $this->redirect($referer);
                    } else {
                        $this->Klant->rollback();
                        $this->flashError(__('The klant could not be saved. Please, try again.', true));
                    }
            }
        } else {
            $this->data['Klant']['referer'] = $this->referer();
            $this->data['Klant']['step'] = 1;
        }

        $geslachten = $this->Klant->Geslacht->find('list');
        $onbekend_land = $this->Klant->Geboorteland->find('first', [
            'conditions' => ['Geboorteland.land =' => 'Onbekend'],
            'fields' => ['land', 'id'],
        ]);

        $landen = [$onbekend_land['Geboorteland']['id'] => $onbekend_land['Geboorteland']['land']];
        $landen = $landen + $this->Klant->Geboorteland->find('list', [
            'order' => ['Geboorteland.land ASC'],
            'conditions' => ['Geboorteland.land !=' => 'Onbekend'],
        ]);

        $default_land_id = array_search('Nederland', $landen);
        $onbekend_nat = $this->Klant->Nationaliteit->find('first', [
            'conditions' => ['Nationaliteit.naam =' => 'Onbekend'],
            'fields' => ['naam', 'id'],
        ]);

        $nationaliteiten = [$onbekend_nat['Nationaliteit']['id'] => $onbekend_nat['Nationaliteit']['naam']];
        $nationaliteiten = $nationaliteiten + $this->Klant->Nationaliteit->find('list', [
            'order' => ['Nationaliteit.naam ASC'],
            'conditions' => ['Nationaliteit.naam !=' => 'Onbekend'],
        ]);

        $default_nationaliteit_id = array_search('Nederlandse', $nationaliteiten);
        $logged_in_user = $this->Session->read('Auth.Medewerker.id');
        $medewerkers = $this->Klant->Medewerker->find('list');
        $werkgebieden = Configure::read('Werkgebieden');
        $postcodegebieden = Configure::read('Postcodegebieden');
        $persoon_model = 'Klant';
        $this->set('duplicates', $dups);
        $this->set('amocCountries', Configure::read('Landen.AMOC'));
        $this->set(compact('geslachten', 'landen', 'nationaliteiten', 'medewerkers', 'default_nationaliteit_id', 'default_land_id', 'logged_in_user'));
        $this->set(compact('id', 'step', 'persoon_model', 'werkgebieden', 'postcodegebieden', 'generic'));

        switch ($step) {
            case 1:
                $this->render('add_1');
                break;
            case 2:
                $this->render('add_2');
                break;
            case 3:
            case 4:
                if (!empty($generic)) {
                    $this->render('edit_generic');
                }
                break;
        }
    }

    private function crmUpdate($id)
    {
        $nationaliteiten = $this->Klant->Nationaliteit->findList();
        $geslachten = $this->Klant->Geslacht->findList();
        $landen = $this->Klant->Geboorteland->findList();
        $mailto = Configure::read('administratiebedrijf');

        $content = [];
        $url = ['controller' => 'klanten', 'action' => 'view', $id];
        $content['url'] = Router::url($url, true);
        $content['changes'] = $this->Klant->changes;

        if (isset($content['changes']['geslacht_id'])) {
            $content['changes']['geslacht'] = $geslachten[$content['changes']['geslacht_id']];
            unset($content['changes']['geslacht_id']);
        }

        if (isset($content['changes']['land_id'])) {
            $content['changes']['land'] = $landen[$content['changes']['land_id']];
            unset($content['changes']['land_id']);
        }

        if (isset($content['changes']['nationaliteit_id'])) {
            $content['changes']['nationaliteit'] = $nationaliteiten[$content['changes']['nationaliteit_id']];
            unset($content['changes']['nationaliteit_id']);
        }

        $this->_genericSendEmail([
            'to' => [$mailto],
            'content' => $content,
            'template' => 'crm',
            'subject' => 'Er heeft een update in het ECD plaatsgevonden',
        ]);
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(['action' => 'index']);
        }

        $generic = $this->getParam('generic');
        $persoon_model = 'Klant';

        if (!empty($this->data) && $id) {
            if (!empty($this->data['Klant']['generic'])) {
                $generic = 1;
            }

            if ($this->Klant->save($this->data)) {
                if (!empty($this->Klant->send_admin_email)) {
                    $this->crmUpdate($id);
                }
                $this->flash(__('The klant has been saved', true));
                $referer = $this->Session->read('basisgegevens_from');
                $referer = $this->data['Klant']['referer'];
                if (!$referer) {
                    $this->redirect(['action' => 'view', $id]);
                } else {
                    $this->Session->write('basisgegevens_from', null);
                    $this->redirect($referer);
                }
            } else {
                $this->flashError(__('The klant could not be saved. Please, try again.', true));
            }
        }

        if (empty($this->data)) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $this->Session->write('basisgegevens_from', $_SERVER['HTTP_REFERER']);
            }

            if (!$this->data = $this->Klant->read(null, $id)) {
                $this->flashError(__('Unknown klant id', true));
                $this->redirect(['action' => 'index']);
            }

            $this->data['Klant']['referer'] = $this->referer();
        }

        $onbekend_land = $this->Klant->Geboorteland->find('first', [
            'conditions' => ['Geboorteland.land =' => 'Onbekend'],
            'fields' => ['land', 'id'],
        ]);

        $landen = [$onbekend_land['Geboorteland']['id'] => $onbekend_land['Geboorteland']['land']];
        $landen = $landen + $this->Klant->Geboorteland->find('list', [
            'order' => ['Geboorteland.land ASC'],
            'conditions' => ['Geboorteland.land !=' => 'Onbekend'],
        ]);

        $geslachten = $this->Klant->Geslacht->find('list');
        $onbekend_nat = $this->Klant->Nationaliteit->find('first', [
            'conditions' => ['Nationaliteit.naam =' => 'Onbekend'],
            'fields' => ['naam', 'id'],
        ]);

        $nationaliteiten = [$onbekend_nat['Nationaliteit']['id'] => $onbekend_nat['Nationaliteit']['naam']];
        $nationaliteiten = $nationaliteiten + $this->Klant->Nationaliteit->find('list', [
            'order' => ['Nationaliteit.naam ASC'],
               'conditions' => ['Nationaliteit.naam !=' => 'Onbekend'],
        ]);

        $this->setMedewerkers([$this->data['Klant']['medewerker_id']]);
        $this->set('amocCountries', Configure::read('Landen.AMOC'));
        $werkgebieden = Configure::read('Werkgebieden');
        $postcodegebieden = Configure::read('Postcodegebieden');

        $this->set(compact('geslachten', 'landen', 'nationaliteiten', 'persoon_model', 'werkgebieden', 'postcodegebieden'));

        if ($generic) {
            $this->render('edit_generic');

            return;
        }
    }

    public function disable($id = null)
    {
        $this->forAdminsOnly();

        if (!$id) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(['action' => 'index']);
        }

        if (!$this->Klant->disable($id)) {
            $this->flashError(__('Klant was not deleted', true));
        } else {
            $this->flash(__('Klant deleted', true));
        }
        $this->redirect(['action' => 'index']);
    }

    public function enable($id = null)
    {
        $this->forAdminsOnly();

        if (!$id) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(['action' => 'index']);
        }

        if (!$this->Klant->enable($id)) {
            $this->flashError(__('Klant was not enabled', true));
        } else {
            $this->flash(__('Klant enabled', true));
        }

        $this->redirect(['action' => 'index']);
    }

    public function disable_many()
    {
        $this->forAdminsOnly();

        if (empty($this->params['pass'])) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(['action' => 'index']);
        }

        $failures = '';

        foreach ($this->params['pass'] as $id) {
            if (!$this->Klant->disable($id)) {
                $failures .= $id.' ';
            }
        }

        if (!empty($failures)) {
            $this->flashError(__('Some clients were not disabled', true).' '.$failures);
        } else {
            $this->flashError(__('Clients disabled', true));
        }
        $this->redirect($this->referer());
    }

    public function printLetter($klantId)
    {
        $klant = $this->Klant->read(null, $klantId);
        $this->set('klant', $klant);
        $this->layout = 'print';
        Configure::write('debug', 0);
    }

    public function upload($klantId = null, $group = null)
    {
        if (empty($klantId)) {
            $klantId = $this->data['Klant']['id'];
        }

        if (empty($group)) {
            $group = $this->data['Document']['group'];
        }

        $klant = $this->Klant->read(null, $klantId);
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
                    'controller' => $this->Klant->Document->groupToController($group),
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

    public function findDuplicates($mode = 'menu')
    {
        if ('menu' === $mode) {
            $this->set('modes', $this->Klant->deduplicationMethods);
            $this->render('find_duplicates_menu');
        } else {
            ini_set('memory_limit', '512M');
            $this->set('duplicates', $this->Klant->findAllDuplicates($mode));
            $this->set('mode', $mode);
        }
    }

    public function merge()
    {
        $ids = array_map('intval', explode(',', $this->passedArgs['ids']));
        $klanten = $this->Klant->find('all', [
            'conditions' => [
                'Klant.id' => $ids,
                'Klant.disabled' => 0,
            ],
            'contain' => [
                'Geslacht', 'Geboorteland', 'Nationaliteit', 'LasteIntake',
                'LaatsteRegistratie',
            ],
        ]);

        if (0 == count($klanten)) {
            $this->flashError(__('Geen klanten gevonden.', true));

            $this->redirect(['action' => 'findDuplicates']);

            return;
        }

        if (!empty($this->data)) {
            $newKlant = $this->getMergedKlanten($klanten);

            $merge_ids = array_intersect(
                    $this->data['Klant']['merge'],
                    Set::classicExtract($klanten, '{n}.Klant.id')
                    );

            $this->Klant->create();
            if ($this->Klant->save($newKlant)) {
                $newKlantId = $this->Klant->id;

                $counts = $this->Klant->moveAssociations(
                        $newKlantId, $merge_ids
                        );

                $this->Klant->disableMultiple($merge_ids, $newKlantId);
                $this->set('counts', $counts);
                $this->set('newKlantId', $newKlantId);
                $this->render('merge_success');
            } else {
                $this->flashError('Het opslaan is niet geslaagd.');
            }
        }

        $this->set('klanten', $klanten);
        $this->set('ids', implode(',', $ids));
    }

    private function getMergedKlanten($klanten)
    {
        $newKlant = $klanten[0]['Klant'];

        unset($newKlant['id']);

        foreach ($klanten as $klant) {
            $id = $klant['Klant']['id'];

            foreach ($this->data['Klant'] as $field => $copyKlantId) {
                if ($id == $copyKlantId) {
                    $newKlant[$field] = $klant['Klant'][$field];
                }
            }
        }

        return $newKlant;
    }
}
