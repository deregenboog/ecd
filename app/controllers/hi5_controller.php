<?php

class Hi5Controller extends AppController
{
    public $name = 'Hi5';

    public $components = [
            'ComponentLoader',
            'RequestHandler',
            'Session',
    ];

    public $uses = [
            'Klant',
    ];

    private $permissions = 0;

    public function __construct()
    {
        parent::__construct();

        if (!defined('HI5_CREATE_INTAKES')) {
            define('HI5_CREATE_INTAKES', 1);
            define('HI5_VIEW_INTAKES', 2);
            define('HI5_CREATE_EVALUATIONS', 4);
            define('HI5_VIEW_EVALUATIONS', 8);
            define('HI5_CREATE_TB_CJ', 16);
            define('HI5_CREATE_WB_CJ', 32);
        }
    }

    public function isAuthorized()
    {
        if (!parent::isAuthorized()) {
            return false;
        }

        if (empty($this->userGroups)) {
            return false;
        }

        return true;
    }

    public function index()
    {
        $this->ComponentLoader->load('Filter');

        if (isset(
            $this->params['named'])) {
            if (isset($this->params['named']['showDisabled'])) {
                $this->Klant->showDisabled = $this->params['named']['showDisabled'];
            }
        }

        $show_all = false;

        if (!empty($this->params['Klant.show_all'])) {
            $show_all = true;
        }

        if (!empty($this->data['Klant']['show_all'])) {
            $show_all = true;
        }

        $this->paginate['Klant'] = [
            'contain' => [
                'Geslacht' => [
                    'fields' => [
                        'afkorting',
                        'volledig',
                    ],
                ],
                'Hi5Intake' => [
                    'fields' => [
                        'locatie1_id',
                        'locatie2_id',
                        'locatie3_id',
                        'datum_intake',
                    ],
                    'order' => 'datum_intake DESC',
                    'Locatie1' => [
                        'naam',
                    ],
                    'Locatie2' => [
                        'naam',
                    ],
                    'Locatie3' => [
                        'naam',
                    ],
                ],
            ],
           'joins' => [
                [
                   'table' => 'trajecten',
                   'alias' => 'Traject',
                   'type' => 'INNER',
                   'conditions' => [
                        'Klant.id = Traject.klant_id',
                   ],
                ],
            ],
        ];

        if ($show_all) {
            unset($this->paginate['Klant']['joins']);
        }

        $klanten = $this->paginate('Klant', $this->Filter->filterData);

        $this->set(compact('klanten'));

        if ($this->RequestHandler->isAjax()) {
            $this->render('/elements/hi5_klantenlijst', 'ajax');
        }
    }

    private function _leftMenuInfo($id)
    {
        $this->Klant->setHi5Info($id);

        $countContactjournalTB = $this->Klant->Contactjournal->find('count', [
            'conditions' => [
                'Contactjournal.klant_id' => $id,
                'Contactjournal.is_tb' => true,
            ],
        ]);

        $countContactjournalWB = $this->Klant->Contactjournal->find('count', [
            'conditions' => [
                'Contactjournal.klant_id' => $id,
                'Contactjournal.is_tb' => false,
            ],
        ]);

        $this->set([
            'klant' => &$this->Klant->data,
        ]);

        $persoon = $this->Klant->getAllById($id);

        $diensten = $this->Klant->diensten($persoon, $this->getEventDispatcher());

        $this->set(compact('countContactjournalWB', 'countContactjournalTB', 'diensten', 'persoon'));
    }

    public function beforeFilter()
    {
        parent::beforeFilter();

        $viewElementOptions = 0;

        if (isset($this->userGroups[GROUP_DEVELOP])) {
            $viewElementOptions |= HI5_CREATE_INTAKES | HI5_VIEW_INTAKES;
            $viewElementOptions |= HI5_VIEW_EVALUATIONS;
            $viewElementOptions |= HI5_CREATE_TB_CJ;
            $viewElementOptions |= HI5_CREATE_EVALUATIONS;
            $viewElementOptions |= HI5_CREATE_WB_CJ;

            $this->permissions = $viewElementOptions;
            $this->set(compact('viewElementOptions'));

            return;
        }

        if (isset($this->userGroups[GROUP_TRAJECTBEGELEIDER])) {
            $viewElementOptions |= HI5_CREATE_INTAKES | HI5_VIEW_INTAKES;
            $viewElementOptions |= HI5_VIEW_EVALUATIONS;
            $viewElementOptions |= HI5_CREATE_TB_CJ;
        }

        if (isset($this->userGroups[GROUP_WERKBEGELEIDER])) {
            $viewElementOptions |= HI5_CREATE_EVALUATIONS | HI5_VIEW_INTAKES;
            $viewElementOptions |= HI5_VIEW_EVALUATIONS;
            $viewElementOptions |= HI5_CREATE_WB_CJ;
        }
        $this->permissions = $viewElementOptions;

        $this->set(compact('viewElementOptions'));
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid klant', true));

            $this->redirect([
                'action' => 'index',
            ]);
        }

        $this->_leftMenuInfo($id);
    }

    public function wijzigen_traject($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(['action' => 'index']);
        }

        $this->Klant->setHi5Info($id);

        if (!empty($this->data)) {
            if (empty($klant['Traject']['id'])) {
                $this->Klant->Traject->create();
            }

            $result = $this->Klant->Traject->save($this->data);

            if ($result) {
                $this->flash(__('Gegevens traject-en werkbegeleiding opgeslagen.', true));

                $this->redirect([
                    'action' => 'view',
                    $id,
                ]);
            }
        } else {
            $this->data = $this->Klant->data;
        }

        $trajectbegeleider_ids = [];
        if (isset($this->Klant->data['Traject']['trajectbegeleider_id'])) {
            $trajectbegeleider_ids[] = $this->Klant->data['Traject']['trajectbegeleider_id'];
        }
        $trajectbegeleiders = ['' => '']
            + $this->Klant->Medewerker->getMedewerkers($trajectbegeleider_ids, [GROUP_TRAJECTBEGELEIDER]);

        $werkbegeleider_ids = [];
        if (isset($this->Klant->data['Traject']['werkbegeleider_id'])) {
            $werkbegeleider_ids[] = $this->Klant->data['Traject']['werkbegeleider_id'];
        }
        $werkbegeleiders = ['' => '']
            + $this->Klant->Medewerker->getMedewerkers($werkbegeleider_ids, [GROUP_WERKBEGELEIDER]);

        $this->_leftMenuInfo($id);

        $this->set([
            'klant' => $this->Klant->data,
            'trajectbegeleiders' => $trajectbegeleiders,
            'werkbegeleiders' => $werkbegeleiders,
        ]);
    }

    public function add_intake($id = null)
    {
        $this->loadModel(ZrmReport::class);
        $zrmReportModel = ZrmReport::getZrmReportModel();
        $this->loadModel($zrmReportModel);

        if (!$id) {
            $this->flashError(__('Invalid klant', true));

            $this->redirect([
                    'action' => 'index',
            ]);
        }

        if (!($this->permissions & HI5_CREATE_INTAKES)) {
            $this->flashError(__('You are not allowed to add intakes', true));
            $this->redirect([
                'action' => 'view',
                $id,
            ]);
        }

        $this->_leftMenuInfo($id);

        $intaker_id = $this->Session->read('Auth.Medewerker.id');

        if (!empty($this->data)) {
            $this->data['Hi5Answer'] = $this->Klant->Hi5Intake->Hi5Answer->processPostedData($this->data['Hi5Answer']);

            $this->Klant->Hi5Intake->begin();

            if ($this->Klant->Hi5Intake->saveAll($this->data, ['atomic' => false])) {
                $this->data[$zrmReportModel]['model'] = 'Hi5Intake';
                $this->data[$zrmReportModel]['foreign_key'] = $this->Klant->Hi5Intake->id;
                $this->data[$zrmReportModel]['klant_id'] = $this->data['Hi5Intake']['klant_id'];

                $this->{$zrmReportModel}->create();

                if ($this->{$zrmReportModel}->save($this->data)) {
                    $this->Klant->Hi5Intake->commit();
                    $this->flash(__('De Hi5 intake is opgeslagen.', true));
                    $this->redirect(['action' => 'view', $id]);
                }

                $this->flashError(__('De Hi5 intake niet opgeslagen.', true));
            } else {
                $this->flashError(__('De Hi5 intake niet opgeslagen.', true));
            }

            $this->Klant->Hi5Intake->rollback();
        } else {
            $this->data = $this->Klant->Hi5Intake->find('first', [
                'conditions' => ['klant_id' => $id],
                'order' => ['Hi5Intake.datum_intake desc', 'Hi5Intake.created desc'],
                'limit' => 1,
            ]);

            if ($this->data) {
                unset($this->data['Hi5Intake']['id']);
            }
            $this->data['Hi5Answer'] = $this->Klant->Hi5Intake->Hi5Answer->processRetrievedData($this->data['Hi5Answer']);

            if (!empty($this->data['Bedrijfitem1']['bedrijfsector_id'])) {
                $this->data['Bedrijfsector1'] = $this->data['Bedrijfitem1']['bedrijfsector_id'];
            }

            if (!empty($this->data['Bedrijfitem2']['bedrijfsector_id'])) {
                $this->data['Bedrijfsector2'] = $this->data['Bedrijfitem2']['bedrijfsector_id'];
            }
        }

        $locatie1s = $this->Klant->Hi5Intake->Locatie1->find('list');
        $locatie2s = $locatie1s;
        $locatie3s = $locatie1s;

        $werklocaties = $locatie1s;

        $legitimaties = $this->Klant->Hi5Intake->Legitimatie->find('list');

        $this->setMedewerkers();

        $verblijfstatussen = $this->Klant->Hi5Intake->Verblijfstatus->find('list', [
                'order' => 'Verblijfstatus.naam ASC',
        ]);

        $primary_problems = $this->Klant->Hi5Intake->PrimaireProblematiek->find('list');

        $verslavingsfrequenties = $this->Klant->Hi5Intake->Verslavingsfrequentie->find('list');
        $verslavingsperiodes = $this->Klant->Hi5Intake->Verslavingsperiode->find('list');
        $verslavingsgebruikswijzen = $this->Klant->Hi5Intake->Verslavingsgebruikswijze->find('list');

        $primaireproblematieksgebruikswijzen = $verslavingsgebruikswijzen;

        $inkomens = $this->Klant->Hi5Intake->Inkomen->find('list');
        $woonsituaties = $this->Klant->Hi5Intake->Woonsituatie->find('list');

        $verslavingen = &$primary_problems;

        $bedrijfItems = $this->Klant->Hi5Intake->Bedrijfitem1->Bedrijfsector->getNestedSectors();
        $bedrijfsectors = $this->Klant->Hi5Intake->Bedrijfitem1->Bedrijfsector->find('list');

        $hi5Questions = $this->Klant->Hi5Intake->Hi5Answer->Hi5Question->getQuestions();

        $zrmData = $this->{$zrmReportModel}->zrm_data();

        $this->set(compact(
            'zrmReportModel', 'zrmData', 'intaker_id', 'verblijfstatussen', 'locatie1s', 'locatie2s', 'locatie3s', 'werklocaties', 'legitimaties',
            'primary_problems', 'verslavingsfrequenties', 'verslavingsperiodes', 'verslavingen',
            'verslavingsgebruikswijzen', 'inkomens', 'woonsituaties', 'bedrijfsectors', 'bedrijfItems', 'hi5Questions',
            'primaireproblematieksgebruikswijzen'
        ));
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

        $this->_leftMenuInfo($id);

        $this->set('klant_id', $id);
        $this->set('klant', $klant);
        $this->set('zrmReports', $zrmReports);
        $this->set('zrmData', $zrmData);
    }

    public function edit_intake($intakeId)
    {
        $intake = $this->Klant->Hi5Intake->find('first', [
            'conditions' => ['Hi5Intake.id' => $intakeId],
        ]);
        if (!$intake) {
            $this->flashError(__('Invalid Intake', true));
            $this->redirect(['action' => 'index']);
        }

        // get ZRM associated with intake
        $this->loadModel(ZrmReport::class);
        foreach (ZrmReport::getZrmReportModels() as $zrmReportModel) {
            $this->loadModel($zrmReportModel);
            $zrmReport = $this->{$zrmReportModel}->get_zrm_report('Hi5Intake', $intake['Hi5Intake']['id']);
            if ($zrmReport) {
                break;
            }
        }
        $zrmData = $this->{$zrmReportModel}->zrm_data();

        $intaker_id = $this->Session->read('Auth.Medewerker.id');

        if (!empty($this->data)) {
            $save = $this->data['Hi5Answer'];

            $this->data['Hi5Intake']['id'] = $intakeId;
            $this->data['Hi5Answer'] = $this->Klant->Hi5Intake->Hi5Answer->processPostedData($this->data['Hi5Answer']);

            $this->Klant->Hi5Intake->commit();

            if ($this->Klant->Hi5Intake->saveAll($this->data, ['atomic' => false])) {
                $this->{$zrmReportModel}->update_zrm_data_for_edit($this->data, 'Hi5Intake', $intakeId, $this->data['Hi5Intake']['klant_id']);

                if ($this->{$zrmReportModel}->save($this->data)) {
                    $this->Klant->Hi5Intake->commit();
                    $this->flash(__('The intake has been saved.', true));
                    $this->redirect(['action' => 'view', $this->data['Hi5Intake']['klant_id']]);
                }
            }

            $this->flashError(__('De intake is niet opgeslagen. Controleer de rood gemarkeerde invoervelden en probeer opnieuw.', true));

            $this->Klant->Hi5Intake->rollback();
            $this->data['Hi5Answer'] = $save;
        } else {
            $this->data = $this->Klant->Hi5Intake->find('first', [
                'conditions' => [
                    'Hi5Intake.id' => $intakeId,
                ],
            ]);

            // can only edit your own intakes
            if ($this->data['Hi5Intake']['medewerker_id'] != $intaker_id) {
                $this->flashError(__('You can only edit your own intakes', true));
                $this->redirect([
                    'action' => 'index',
                    $this->data['Hi5Intake']['klant_id'],
                ]);
            }

            // can only edit recent intakes
            if (date('Y-m-d', strtotime($this->data['Hi5Intake']['created'])) !== date('Y-m-d')) {
                $this->flashError(__('You can only edit intakes the same day of their creation', true));
                $this->redirect([
                    'action' => 'view',
                    $this->data['Hi5Intake']['klant_id'],
                ]);
            }

            if (!($this->permissions & HI5_CREATE_INTAKES)) {
                $this->flashError(__('You are not allowed to edit intakes', true));
                $this->redirect([
                    'action' => 'view',
                    $this->data['Hi5Intake']['klant_id'],
                ]);
            }

            $this->data['Hi5Answer'] = $this->Klant->Hi5Intake->Hi5Answer->processRetrievedData($this->data['Hi5Answer']);
        }

        $this->_leftMenuInfo($this->data['Hi5Intake']['klant_id']);

        $locatie1s = $this->Klant->Hi5Intake->Locatie1->find('list');
        $locatie2s = $locatie1s;
        $locatie3s = $locatie1s;
        $werklocaties = $locatie1s;
        $legitimaties = $this->Klant->Hi5Intake->Legitimatie->find('list');
        $this->setMedewerkers();
        $verblijfstatussen = $this->Klant->Hi5Intake->Verblijfstatus->find('list', ['order' => 'Verblijfstatus.naam ASC']);
        $primary_problems = $this->Klant->Hi5Intake->PrimaireProblematiek->find('list');
        $verslavingsfrequenties = $this->Klant->Hi5Intake->Verslavingsfrequentie->find('list');
        $verslavingsperiodes = $this->Klant->Hi5Intake->Verslavingsperiode->find('list');
        $verslavingsgebruikswijzen = $this->Klant->Hi5Intake->Verslavingsgebruikswijze->find('list');
        $primaireproblematieksgebruikswijzen = $verslavingsgebruikswijzen;
        $inkomens = $this->Klant->Hi5Intake->Inkomen->find('list');
        $woonsituaties = $this->Klant->Hi5Intake->Woonsituatie->find('list');
        $verslavingen = &$primary_problems;
        $bedrijfItems = $this->Klant->Hi5Intake->Bedrijfitem1->Bedrijfsector->getNestedSectors();
        $bedrijfsectors = $this->Klant->Hi5Intake->Bedrijfitem1->Bedrijfsector->find('list');

        if (!empty($this->data['Bedrijfitem1']['bedrijfsector_id'])) {
            $this->data['Bedrijfsector1'] = $this->data['Bedrijfitem1']['bedrijfsector_id'];
        }

        if (!empty($this->data['Bedrijfitem2']['bedrijfsector_id'])) {
            $this->data['Bedrijfsector2'] = $this->data['Bedrijfitem2']['bedrijfsector_id'];
        }

        $hi5Questions = $this->Klant->Hi5Intake->Hi5Answer->Hi5Question->getQuestions();

        if (empty($this->data[$zrmReportModel])) {
            $zrm = $zrmReport;
            $this->data[$zrmReportModel] = $zrm[$zrmReportModel];
        }

        $this->set(compact(
            'zrmReportModel', 'zrmData', 'intaker_id', 'verblijfstatussen', 'locatie1s', 'locatie2s', 'locatie3s', 'werklocaties', 'legitimaties',
            'primary_problems', 'verslavingsfrequenties', 'verslavingsperiodes', 'verslavingen',
            'verslavingsgebruikswijzen', 'inkomens', 'woonsituaties', 'bedrijfsectors', 'bedrijfItems', 'hi5Questions',
            'primaireproblematieksgebruikswijzen'
        ));
    }

    public function add_evaluatie($klantId)
    {
        if (!$klantId) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect([
                    'action' => 'index',
            ]);
        }

        if (!($this->permissions & HI5_CREATE_EVALUATIONS)) {
            $this->flashError(__('You are not allowed to add evaluations', true));
            $this->redirect([
                    'action' => 'view',
                    $klantId,
            ]);
        }

        $this->_leftMenuInfo($klantId);

        $intaker_id = $this->Session->read('Auth.Medewerker.id');

        if (!empty($this->data)) {
            $this->data['Hi5EvaluatieQuestion'] = $this->Klant->Hi5Evaluatie->Hi5EvaluatieQuestion->processPostedData(
                $this->data['Hi5EvaluatieQuestion']);

            $this->data['datumevaluatie'] = date('Y-m-d');

            $this->Klant->Hi5Evaluatie->create();

            $result = $this->Klant->Hi5Evaluatie->saveAll($this->data);

            if ($result) {
                $this->flash(__('The evaluation has been saved.', true));
                $this->redirect(
                    [
                            'action' => 'view',
                            $klantId,
                    ]);
            } else {
                $this->flashError('Fout op het formulier.');
            }
        }

        $medewerkers = $this->setMedewerkers();

        for ($i = 1; $i < 10; ++$i) {
            $aantal_dagdelens[$i] = "$i dagdelen";
        }

        $paragraphs = $this->Klant->Hi5Evaluatie->Hi5EvaluatieQuestion->Hi5EvaluatieParagraph->getParagraphs();

        $this->set(compact('intaker_id', 'aantal_dagdelens', 'paragraphs'));
    }

    public function edit_evaluatie($evaluatieId)
    {
        if (!$evaluatieId) {
            $this->flashError(__('Invalid Evaluatie', true));
            $this->redirect([
                    'action' => 'index',
            ]);
        }

        $intaker_id = $this->Session->read('Auth.Medewerker.id');

        if (!empty($this->data)) {
            $this->data['Hi5EvaluatieQuestion']['id'] = $evaluatieId;

            $this->data['Hi5EvaluatieQuestion'] = $this->Klant->Hi5Evaluatie->Hi5EvaluatieQuestion->processPostedData(
                $this->data['Hi5EvaluatieQuestion']);

            $this->Klant->Hi5Evaluatie->create();

            $result = $this->Klant->Hi5Evaluatie->saveAll($this->data);

            if ($result) {
                $this->flash(__('The evaluation has been saved.', true));
                $this->redirect(
                    [
                            'action' => 'view',
                            $this->data['Hi5Evaluatie']['klant_id'],
                    ]);
            }

            $this->data = $this->Klant->Hi5Evaluatie->read();
        } else {
            $this->data = $this->Klant->Hi5Evaluatie->find('first', [
                'conditions' => [
                    'Hi5Evaluatie.id' => $evaluatieId,
                ],
            ]);

            $this->data['Hi5EvaluatieQuestion'] = $this->Klant->Hi5Evaluatie->Hi5EvaluatieQuestion->processRetrievedData($this->data['Hi5EvaluatieQuestion']);

            if ($this->data['Hi5Evaluatie']['medewerker_id'] != $intaker_id) {
                $this->flashError(__('You can only edit your own evaluaties', true));
                $this->redirect([
                    'action' => 'view',
                    $this->data['Hi5Evaluatie']['klant_id'],
                ]);
            }

            if (date('Y-m-d', strtotime($this->data['Hi5Evaluatie']['created'])) !== date('Y-m-d')) {
                $this->flashError(__('You can only edit evaluaties the same day of their creation', true));
                $this->redirect([
                    'action' => 'view',
                    $this->data['Hi5Evaluatie']['klant_id'],
                ]);
            }

            if (!$this->permissions & HI5_CREATE_EVALUATIONS) {
                $this->flashError(__('You are not allowed to edit intakes', true));
                $this->redirect([
                    'action' => 'view',
                    $this->data['Hi5Evaluatie']['klant_id'],
                ]);
            }
        }

        $this->_leftMenuInfo($this->data['Hi5Evaluatie']['klant_id']);
        $this->setMedewerkers();

        for ($i = 1; $i < 10; ++$i) {
            $aantal_dagdelens[$i] = "$i dagdelen";
        }

        $paragraphs = $this->Klant->Hi5Evaluatie->Hi5EvaluatieQuestion->Hi5EvaluatieParagraph->getParagraphs();

        $this->set(compact('intaker_id', 'aantal_dagdelens', 'paragraphs'));
    }

    public function view_intake($intakeId = null)
    {
        if (!$intakeId) {
            $this->flashError(__('Invalid Intake', true));
            $this->redirect([
                    'action' => 'index',
            ]);
        }

        $intake = $this->Klant->Hi5Intake->find('first', [
            'conditions' => [
                'Hi5Intake.id' => $intakeId,
            ],
            'recursive' => 2,
            'contain' => [
                'Medewerker',
                'Verblijfstatus',
                'Locatie1',
                'Locatie2',
                'Locatie3',
                'Werklocatie',
                'Legitimatie',
                'PrimaireProblematiek',
                'PrimaireProblematieksperiode',
                'Verslaving',
                'Verslavingsfrequentie',
                'Verslavingsperiode',
                'Verslavingsgebruikswijze',
                'Inkomen',
                'Woonsituatie',
                'Bedrijfitem1' => [
                    'Bedrijfsector',
                ],
                'Bedrijfitem2' => [
                    'Bedrijfsector',
                ],
                'Primaireproblematieksgebruikswijze',
                'Hi5Answer',
            ],
        ]);

        $hi5Questions = $this->Klant->Hi5Intake->Hi5Answer->Hi5Question->getQuestions();

        $this->set('intake', $intake);
        $this->set('hi5Questions', $hi5Questions);

        $klantId = $intake['Hi5Intake']['klant_id'];

        $this->_leftMenuInfo($klantId);

        // get ZRM associated with intake
        $this->loadModel(ZrmReport::class);
        foreach (ZrmReport::getZrmReportModels() as $zrmReportModel) {
            $this->loadModel($zrmReportModel);
            $zrmReport = $this->{$zrmReportModel}->get_zrm_report('Hi5Intake', $intakeId);
            if ($zrmReport) {
                $zrmData = $this->{$zrmReportModel}->zrm_data();
                break;
            }
        }
        $this->set(compact('zrmData', 'zrmReport'));
    }

    public function print_empty_intake()
    {
        $hi5Questions = $this->Klant->Hi5Intake->Hi5Answer->Hi5Question->getQuestions();

        $this->set('locaties', $this->Klant->Hi5Intake->Locatie1->find('list'));

        $this->set('legitimaties', $this->Klant->Hi5Intake->Legitimatie->find('list'));

        $this->set('verblijfstatussen', $this->Klant->Hi5Intake->Verblijfstatus->find('list', [
            'order' => 'Verblijfstatus.naam ASC',
        ]));

        $this->set('problems', $this->Klant->Hi5Intake->PrimaireProblematiek->find('list'));

        $this->set('verslavingsfrequenties', $this->Klant->Hi5Intake->Verslavingsfrequentie->find('list'));

        $this->set('verslavingsperiodes', $this->Klant->Hi5Intake->Verslavingsperiode->find('list'));

        $this->set('verslavingsgebruikswijzen', $this->Klant->Hi5Intake->Verslavingsgebruikswijze->find('list'));

        $this->set('inkomens', $this->Klant->Hi5Intake->Inkomen->find('list'));

        $this->set('woonsituaties', $this->Klant->Hi5Intake->Woonsituatie->find('list'));

        $this->set('bedrijfSectors', $this->Klant->Hi5Intake->Bedrijfitem1->Bedrijfsector->find('list'));

        $this->set('hi5Questions', $hi5Questions);
    }

    public function print_empty_evaluatie()
    {
        $Hi5EvaluatieParagraph = $this->Klant->Hi5Evaluatie->Hi5EvaluatieQuestion->Hi5EvaluatieParagraph;

        $this->set('paragraphs', $Hi5EvaluatieParagraph->getParagraphs());
    }

    public function view_evaluatie($evaluatieId = null)
    {
        if (!$evaluatieId) {
            $this->flashError(__('Invalid Evaluatie', true));
            $this->redirect([
                    'action' => 'index',
            ]);
        }

        $evaluatie = $this->Klant->Hi5Evaluatie->find('first', [
            'conditions' => [
                'Hi5Evaluatie.id' => $evaluatieId,
            ],
            'recursive' => 1,
        ]);

        $this->set('evaluatie', $evaluatie);

        $this->_leftMenuInfo($evaluatie['Klant']['id']);

        $intaker_id = $this->Session->read('Auth.Medewerker.id');

        $paragraphs = $this->Klant->Hi5Evaluatie->Hi5EvaluatieQuestion->Hi5EvaluatieParagraph->getParagraphs();

        $this->set(compact('medewerkers', 'paragraphs'));
    }

    private function _getContactJournals($klantId, $isTb)
    {
        $contactJournals = $this->Klant->Contactjournal->find('all', [
            'conditions' => [
                'Contactjournal.klant_id' => $klantId,
                'Contactjournal.is_tb' => (bool) $isTb,
            ],
            'order' => [
                'Contactjournal.datum DESC',
            ],
        ]);

        $this->set(compact('contactJournals'));
    }

    public function contactjournal($klantId = null, $isTb = true)
    {
        if (!$klantId) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect([
                    'action' => 'index',
            ]);
        }

        $this->_leftMenuInfo($klantId);
        $this->_checkContactJournalPermisssions($klantId, $isTb);
        $this->_getContactJournals($klantId, $isTb);

        if (!empty($this->data)) {
            $result = $this->Klant->Contactjournal->saveAll($this->data);

            if ($result) {
                $this->flash(__('Notitie succesvol opgeslagen.', true));

                $this->redirect([
                    'action' => 'contactjournal',
                    $klantId,
                    $isTb,
                ]);
            } else {
                $this->flashError('Fout op het formulier.');
            }
        } else {
            $this->data = [
                'Contactjournal' => [
                    'klant_id' => $klantId,
                    'is_tb' => $isTb,
                ],
            ];
        }

        $this->setMedewerkers();
    }

    private function _checkContactJournalPermisssions($klantId, $isTb)
    {
        if ($isTb && !($this->permissions & HI5_CREATE_TB_CJ)) {
            $this->flashError(__('You are not allowed to view, edit or create trajectbegeleider contactjournal', true));
            $this->redirect([
                    'action' => 'view',
                    $klantId,
            ]);
        }

        if (!$isTb && !($this->permissions & HI5_CREATE_WB_CJ)) {
            $this->flashError(__('You are not allowed to view, edit or create werkbegeleider contactjournal', true));
            $this->redirect([
                    'action' => 'view',
                    $klantId,
            ]);
        }
    }

    public function cj_edit($contactjournalId)
    {
        if (!$contactjournalId) {
            $this->flashError(__('Invalid contactjournal', true));
            $this->redirect([
                    'action' => 'index',
            ]);
        }

        $this->Klant->Contactjournal->id = $contactjournalId;
        $contactjournal = $this->Klant->Contactjournal->read();

        $klantId = $contactjournal['Contactjournal']['klant_id'];
        $isTb = $contactjournal['Contactjournal']['is_tb'];

        $this->_checkContactJournalPermisssions($klantId, $isTb);
        $this->_getContactJournals($klantId, $isTb);

        if (!empty($this->data)) {
            $result = $this->Klant->Contactjournal->saveAll($this->data);

            if ($result) {
                $this->flash(__('Notitie succesvol opgeslagen.', true));
                $this->redirect([
                    'action' => 'contactjournal',
                    $contactjournal['Contactjournal']['klant_id'],
                    $contactjournal['Contactjournal']['is_tb'],
                ]);
            } else {
                $this->flashError('Fout op het formulier.');
            }
        } else {
            $this->data = $contactjournal;
        }

        $this->set('klantId', $contactjournal['Contactjournal']['klant_id']);

        $this->setMedewerkers();

        $this->_leftMenuInfo($contactjournal['Contactjournal']['klant_id']);

        $this->render('/hi5/contactjournal');
    }

    public function cj_delete($contactjournalId = null)
    {
        if (!$contactjournalId) {
            $this->flashError(__('Invalid contactjournal', true));

            $this->redirect([
                'action' => 'index',
            ]);
        }
        $this->Klant->Contactjournal->id = $contactjournalId;

        $contactjournal = $this->Klant->Contactjournal->read();

        $this->_checkContactJournalPermisssions($contactjournal['Contactjournal']['klant_id'], $contactjournal['Contactjournal']['is_tb']);

        $result = $this->Klant->Contactjournal->delete($contactjournalId);

        if ($result) {
            $this->flash(__('Notitie verwijderd.', true));
            $this->redirect([
                'action' => 'contactjournal',
                $contactjournal['Contactjournal']['klant_id'],
                $contactjournal['Contactjournal']['is_tb'],
            ]);
        }
    }
}
