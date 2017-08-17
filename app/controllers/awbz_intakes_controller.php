<?php

class AwbzIntakesController extends AppController
{
    public $name = 'AwbzIntakes';

    public function beforeFilter()
    {
        $this->set('intake_type', 'awbz');
        parent::beforeFilter();
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid intake', true));
            $this->redirect(['action' => 'index']);
        }

        $intake = $this->AwbzIntake->read(null, $id);
        $klant = $this->AwbzIntake->Klant->read(null, $intake['Klant']['id']);

        App::import('Helper', 'Date');
        $dateHelper = new DateHelper();
        $title_for_layout = ' - AwbzIntake van '.$intake['Klant']['name'].' op '.$dateHelper->show($intake['AwbzIntake']['datum_intake']);

        $indicaties_counter = $this->_get_indicaties_counter($intake['Klant']['id']);

        $this->set(compact('klant', 'intake', 'title_for_layout', 'indicaties_counter'));

        $this->loadModel(ZrmReport::class);
        foreach (ZrmReport::getZrmReportModels() as $zrmReportModel) {
            $this->loadModel($zrmReportModel);
            $zrmReport = $this->{$zrmReportModel}->get_zrm_report('AwbzIntake', $id);
            if ($zrmReport) {
                break;
            }
        }

        $this->set('zrmReport', $zrmReport);
        $this->set('zrmData', $this->{$zrmReportModel}->zrm_data());
    }

    public function add($klant_id = null)
    {
        $this->loadModel(ZrmReport::class);
        $zrmReportModel = ZrmReport::getZrmReportModel();
        $this->loadModel($zrmReportModel);

        if (!empty($this->data)) {
            $this->AwbzIntake->create();
            if ($this->AwbzIntake->saveAll($this->data)) {
                $this->AwbzIntake->begin();
                $this->data[$zrmReportModel]['model'] = 'AwbzIntake';
                $this->data[$zrmReportModel]['foreign_key'] = $this->AwbzIntake->id;
                $this->data[$zrmReportModel]['klant_id'] = $klant_id;

                $this->{$zrmReportModel}->create();

                if ($this->{$zrmReportModel}->save($this->data)) {
                    $this->AwbzIntake->commit();
                    $this->sendAwbzIntakeNotification($this->AwbzIntake->id, $this->data);
                    $this->flash(__('De intake is opgeslagen', true));
                    $this->redirect(['controller' => 'awbz', 'action' => 'view', $klant_id]);
                }
                $this->flashError(__('De intake is niet opgeslagen. Controleer de rood gemarkeerde invoervelden en probeer opnieuw.', true));
            } else {
                $this->flashError(__('De intake is niet opgeslagen. Controleer de rood gemarkeerde invoervelden en probeer opnieuw.', true));
            }
            $this->AwbzIntake->rollback();

            $intaker_id = $this->data['AwbzIntake']['medewerker_id'];
        } else {
            $intaker_id = $this->Session->read('Auth.Medewerker.id');
        }

        if ($klant_id == null) {
            $this->flashError('Geen klant Id opgegeven');
            $this->redirect(['controller' => 'klanten', 'action' => 'index']);
        }
        $klant = $this->AwbzIntake->Klant->read(null, $klant_id);

        if (empty($this->data)) {
            $this->_get_data_from_last_intakes($klant);
            $datum_intake = date('Y-m-d');
        } else {
            $datum_intake = null;
        }

        $informele_zorg_mail = Configure::read('informele_zorg_mail');
        $dagbesteding_mail = Configure::read('dagbesteding_mail');
        $inloophuis_mail = Configure::read('inloophuis_mail');
        $hulpverlening_mail = Configure::read('hulpverlening_mail');

        $this->setMedewerkers();
        $verblijfstatussen = $this->AwbzIntake->Verblijfstatus->find('list', ['order' => 'Verblijfstatus.naam ASC']);
        $legitimaties = $this->AwbzIntake->Legitimatie->find('list');
        $verslavingsfrequenties = $this->AwbzIntake->Verslavingsfrequentie->find('list');
        $verslavingsperiodes = $this->AwbzIntake->Verslavingsperiode->find('list');
        $woonsituaties = $this->AwbzIntake->Woonsituatie->find('list');

        $locatie1s = $this->AwbzIntake->Locatie1->find('list');
        $locatie2s = $locatie1s;

        $inkomens = $this->AwbzIntake->Inkomen->find('list');
        $instanties = $this->AwbzIntake->Instantie->find('list');
        $verslavingsgebruikswijzen = $this->AwbzIntake->Verslavingsgebruikswijze->find('list');
        $verslavingen = $this->AwbzIntake->Verslaving->find('list');
        $primary_problems = $this->AwbzIntake->PrimaireProblematiek->find('list');
        $primaireproblematieksgebruikswijzen = $verslavingsgebruikswijzen;

        $indicaties_counter = $this->_get_indicaties_counter($klant_id);

        $zrmData = $this->{$zrmReportModel}->zrm_data();

        $this->set(compact('zrmData', 'zrmReportModel', 'primary_problems', 'klant', 'medewerkers',
            'verblijfstatussen', 'legitimaties', 'verslavingsfrequenties',
            'verslavingsperiodes', 'woonsituaties', 'locatie1s', 'locatie2s',
            'inkomens', 'instanties', 'verslavingsgebruikswijzen',
            'verslavingen', 'intaker_id', 'datum_intake', 'indicaties_counter',
            'primaireproblematieksgebruikswijzen', 'informele_zorg_mail',
            'dagbesteding_mail', 'inloophuis_mail', 'hulpverlening_mail'
        ));
    }

    public function _get_data_from_last_intakes($klant)
    {
        $this->AwbzIntake->recursive = -1;
        $last_awbz_intake = $this->AwbzIntake->find('first', [
            'conditions' => [
                'AwbzIntake.klant_id' => $klant['Klant']['id'],
            ],
            'order' => 'AwbzIntake.datum_intake DESC, AwbzIntake.created DESC',
        ]);

        $last_r_intake = $klant['LasteIntake'];

        if (empty($last_awbz_intake) && empty($last_r_intake)) {
        } else {
            $this->data = $last_awbz_intake;

            if (!empty($last_r_intake)) {
                $r_date = strtotime($last_r_intake['datum_intake']);
            } else {
                $r_date = 0;
            }

            if (!empty($last_awbz_intake)) {
                $awbz_date =
                    strtotime($last_awbz_intake['AwbzIntake']['datum_intake']);
            } else {
                $awbz_date = 0;
            }

            if ($r_date > $awbz_date) {
                foreach (['postadres', 'postcode', 'woonplaats',
                    'verblijfstatus_id', 'verblijf_in_NL_sinds',
                    'verblijf_in_amsterdam_sinds', ] as $field
                ) {
                    if (!empty($last_r_intake[$field]));
                    $this->data['AwbzIntake'][$field] =
                        $last_r_intake[$field];
                }
            }
        }

        $intaker_id = $this->Session->read('Auth.Medewerker.id');
        $this->data['AwbzIntake']['medewerker_id'] = $intaker_id;

        unset($this->data['AwbzIntake']['id']);
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Ongeldige intake', true));
            $this->redirect([
                'controller' => 'awbz',
                'action' => 'index',
            ]);
        }

        // get ZRM associated with intake
        $this->loadModel(ZrmReport::class);
        foreach (ZrmReport::getZrmReportModels() as $zrmReportModel) {
            $this->loadModel($zrmReportModel);
            $zrm = $this->{$zrmReportModel}->get_zrm_report('AwbzIntake', $id);
            if ($zrm) {
                break;
            }
        }

        if (!empty($this->data)) {
            $this->AwbzIntake->begin();
            if ($this->AwbzIntake->save($this->data)) {
                $this->{$zrmReportModel}->update_zrm_data_for_edit($this->data, 'AwbzIntake', $id, $this->data['AwbzIntake']['klant_id']);
                $this->{$zrmReportModel}->create();
                if ($this->{$zrmReportModel}->save($this->data)) {
                    $this->sendAwbzIntakeNotification($this->AwbzIntake->id, $this->data);
                    $this->flash(__('De intake is opgeslagen', true));
                    $this->AwbzIntake->commit();
                    $this->redirect(['controller' => 'awbz', 'action' => 'view', $this->data['AwbzIntake']['klant_id']]);
                }
                $this->flashError(__('De intake is niet opgeslagen. Controleer de rood gemarkeerde invoervelden en probeer opnieuw.', true));
            } else {
                $this->flashError(__('De intake is niet opgeslagen. Controleer de rood gemarkeerde invoervelden en probeer opnieuw.', true));
            }
            $this->AwbzIntake->rollback();
        } else {
            $this->AwbzIntake->recursive = 1;
            $this->data = $this->AwbzIntake->read(null, $id);
        }

        $klant_id = $this->data['AwbzIntake']['klant_id'];

        if ($this->data['AwbzIntake']['datum_intake'] != date('Y-m-d')) {
            $this->flashError(__(
                'You can only edit intakes that have been created today.',
                true
            ));
        }

        $logged_in_user_id = $this->Session->read('Auth.Medewerker.id');
        if ($this->data['AwbzIntake']['medewerker_id'] != $logged_in_user_id) {
            $this->flashError(__('You can only edit intakes that you created.', true));
            $this->redirect([
                'controller' => 'awbz',
                'action' => 'view',
                $klant_id,
            ]);
        }

        $informele_zorg_mail = Configure::read('informele_zorg_mail');
        $dagbesteding_mail = Configure::read('dagbesteding_mail');
        $inloophuis_mail = Configure::read('inloophuis_mail');
        $hulpverlening_mail = Configure::read('hulpverlening_mail');

        $this->setMedewerkers();

        $verblijfstatussen = $this->AwbzIntake->Verblijfstatus->find('list', ['order' => 'Verblijfstatus.naam ASC']);
        $legitimaties = $this->AwbzIntake->Legitimatie->find('list');
        $verslavingsfrequenties = $this->AwbzIntake->Verslavingsfrequentie->find('list');
        $verslavingsperiodes = $this->AwbzIntake->Verslavingsperiode->find('list');
        $woonsituaties = $this->AwbzIntake->Woonsituatie->find('list');

        $locatie1s = $this->AwbzIntake->Locatie1->find('list');
        $locatie2s = $locatie1s;

        $inkomens = $this->AwbzIntake->Inkomen->find('list');
        $instanties = $this->AwbzIntake->Instantie->find('list');
        $verslavingsgebruikswijzen = $this->AwbzIntake->Verslavingsgebruikswijze->find('list');
        $verslavingen = $this->AwbzIntake->Verslaving->find('list');
        $primary_problems = $this->AwbzIntake->PrimaireProblematiek->find('list');
        $primaireproblematieksgebruikswijzen = $verslavingsgebruikswijzen;

        $indicaties_counter = $this->_get_indicaties_counter($klant_id);

        $this->set(compact('primary_problems', 'klant', 'medewerkers',
            'verblijfstatussen', 'legitimaties', 'verslavingsfrequenties',
            'verslavingsperiodes', 'woonsituaties', 'locatie1s', 'locatie2s',
            'inkomens', 'instanties', 'verslavingsgebruikswijzen',
            'verslavingen', 'intaker_id', 'datum_intake', 'indicaties_counter',
            'primaireproblematieksgebruikswijzen', 'informele_zorg_mail',
            'dagbesteding_mail', 'inloophuis_mail', 'hulpverlening_mail'
        ));

        $klant = $this->AwbzIntake->Klant->findById($klant_id);

        if (empty($this->data[$zrmReportModel])) {
            $zrm = $this->{$zrmReportModel}->get_zrm_report('AwbzIntake', $id);
            $this->data[$zrmReportModel] = $zrm[$zrmReportModel];
        }

        $zrmData = $this->{$zrmReportModel}->zrm_data();
        $this->set('zrmReportModel', $zrmReportModel);
        $this->set(compact('klant', 'zrmData'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Ongeldige id voor intake', true));
            $this->redirect(['action' => 'index']);
        }
        if ($this->AwbzIntake->delete($id)) {
            $this->flashError(__('AwbzIntake verwijderd', true));
            $this->redirect(['action' => 'index']);
        }
        $this->flashError(__('AwbzIntake is niet verwijderd', true));
        $this->redirect(['action' => 'index']);
    }

    public function sendAwbzIntakeNotification($intake_id, &$data)
    {
        $intake = &$data['AwbzIntake'];

        if (!isset($intake['informele_zorg']) || !isset($intake['dagbesteding'])
            || !isset($intake['inloophuis']) || !isset($intake['hulpverlening'])
        ) {
            return;
        }

        $addresses = [];
        if ((isset($intake['informele_zorg_ignore']) &&
            !$intake['informele_zorg_ignore'] && $intake['informele_zorg'])
            ||
            (!isset($intake['informele_zorg_ignore']) &&
            $intake['informele_zorg'])
        ) {
            $addresses[] = Configure::read('informele_zorg_mail');
        }

        if ((isset($intake['dagbesteding_ignore']) &&
            !$intake['dagbesteding_ignore'] && $intake['dagbesteding'])
            ||
            (!isset($intake['dagbesteding_ignore']) &&
            $intake['dagbesteding'])
        ) {
            $addresses[] = Configure::read('dagbesteding_mail');
        }

        if ((isset($intake['inloophuis_ignore']) &&
            !$intake['inloophuis_ignore'] && $intake['inloophuis'])
            ||
            (!isset($intake['inloophuis_ignore']) &&
            $intake['inloophuis'])
        ) {
            $addresses[] = Configure::read('inloophuis_mail');
        }

        if ((isset($intake['hulpverlening_ignore']) &&
            !$intake['hulpverlening_ignore'] && $intake['hulpverlening'])
            ||
            (!isset($intake['hulpverlening_ignore']) &&
            $intake['hulpverlening'])
        ) {
            $addresses[] = Configure::read('hulpverlening_mail');
        }

        if (count($addresses) > 0) {
            $intake = $this->AwbzIntake->find('first', [
                'conditions' => ['AwbzIntake.id' => $intake_id],
                'contain' => $this->AwbzIntake->contain,
            ]);

            $this->_genericSendEmail([
                'to' => $addresses,
                'content' => $intake,
            ]);
        }
    }

    public function _get_indicaties_counter($klant_id)
    {
        $indicaties_counter =
            $this->AwbzIntake->Klant->AwbzIndicatie->find('count', [
                'conditions' => ['AwbzIndicatie.klant_id' => $klant_id],
            ]);

        if ($indicaties_counter > 1) {
            return "($indicaties_counter indicaties)";
        } elseif ($indicaties_counter == 1) {
            return "($indicaties_counter indicatie)";
        } else {
            return '';
        }
    }
}
