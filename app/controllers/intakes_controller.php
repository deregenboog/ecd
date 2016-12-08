<?php

class IntakesController extends AppController
{
    public $name = 'Intakes';

    public function index()
    {
        $this->Intake->recursive = 0;
        $this->set('intakes', $this->paginate());
    }

    public function view($id = null)
    {
        $this->loadModel('ZrmReport');
        if (!$id) {
            $this->flashError(__('Invalid intake', true));
            $this->redirect(array('action' => 'index'));
        }

        $intake = $this->Intake->read(null, $id);
        $klant = $this->Intake->Klant->read(null, $intake['Klant']['id']);

        App::import('Helper', 'Date');
        $dateHelper = new DateHelper();

        $title_for_layout = ' - Intake van '.
            $intake['Klant']['name'].' op '.
            $dateHelper->show($intake['Intake']['datum_intake']);

        $this->set('intake', $intake);
        $this->set('klant', $klant);
        $zrm_data = $this->ZrmReport->zrm_data();

        $zrmReport = $this->ZrmReport->get_zrm_report('Intake', $id, $intake['Klant']['id']);
        $this->set('diensten', $this->Intake->Klant->diensten($intake['Intake']['klant_id']));

        $klant = $this->Intake->Klant->findById($intake['Intake']['klant_id']);
        $this->set(compact('title_for_layout', 'zrm_data', 'zrmReport'));
    }

    public function add($klant_id = null)
    {
        $this->loadModel('ZrmReport');
        if (!empty($this->data)) {
            $this->Intake->create();
            $this->Intake->begin();

            if ($this->Intake->saveAll($this->data, array('atomic' => false))) {
                $this->data['ZrmReport']['model'] = 'Intake';
                $this->data['ZrmReport']['foreign_key'] = $this->Intake->id;
                $this->data['ZrmReport']['klant_id'] = $klant_id;

                $this->ZrmReport->create();

                if ($this->ZrmReport->save($this->data)) {
                    $this->Intake->commit();
                    $this->flash(__('De intake is opgeslagen', true));

                    $this->sendIntakeNotification($this->Intake->id, $this->data);
                    $this->redirect(array('controller' => 'klanten', 'action' => 'view', $klant_id));
                }

                $this->flashError(__('De intake is niet opgeslagen. Controleer de rood gemarkeerde invoervelden en probeer opnieuw.', true));
            } else {
                $this->flashError(__('De intake is niet opgeslagen. Controleer de rood gemarkeerde invoervelden en probeer opnieuw.', true));
            }
            $this->Intake->rollback();
            $intaker_id = $this->data['Intake']['medewerker_id'];
        } else {
            $intaker_id = $this->Session->read('Auth.Medewerker.id');
        }

        if ($klant_id == null) {
            $this->flashError('Geen klant Id opgegeven');
            $this->redirect(array('controller' => 'klanten', 'action' => 'index'));
        }
        $this->Intake->Klant->recursive = 1;
        $klant = $this->Intake->Klant->read(null, $klant_id);

        $datum_intake = date('Y-m-d');
        if (empty($this->data) && !empty($klant['Klant']['laste_intake_id'])) {
            $current = $this->Intake->findById(
                    $klant['Klant']['laste_intake_id']);
            if (isset($current['Intake']['id'])) {
                unset($current['Intake']['id']);
            }
            $intaker_id = $this->Session->read('Auth.Medewerker.id');
            $this->data = $current;
            $this->data['Intake']['medewerker_id'] = $intaker_id;
        } else {
            if (is_array($this->data['Intake']['datum_intake'])) {
                $date = $this->data['Intake']['datum_intake'];
                if ($date['year'] != 0 && $date['month'] != 0 && $date['day'] != 0) {
                    $datum_intake = $date;
                }
            }
        }

        $informele_zorg_mail = Configure::read('informele_zorg_mail');
        $dagbesteding_mail = Configure::read('dagbesteding_mail');
        $inloophuis_mail = Configure::read('inloophuis_mail');
        $hulpverlening_mail = Configure::read('hulpverlening_mail');

        $medewerkers = $this->Intake->Medewerker->find('list');
        $verblijfstatussen = $this->Intake->Verblijfstatus->find('list', array('order' => 'Verblijfstatus.naam ASC'));
        $legitimaties = $this->Intake->Legitimatie->find('list');
        $verslavingsfrequenties = $this->Intake->Verslavingsfrequentie->find('list');
        $verslavingsperiodes = $this->Intake->Verslavingsperiode->find('list');
        $woonsituaties = $this->Intake->Woonsituatie->find('list');

        $locatie1s = $this->Intake->Locatie1->find('list', array('conditions' => array('Locatie1.gebruikersruimte' => 1)));
        $locatie2s = $this->Intake->Locatie2->find('list', array('conditions' => array()));
        $locatie3s = array();

        $inkomens = $this->Intake->Inkomen->find('list');
        $instanties = $this->Intake->Instantie->find('list');
        $verslavingsgebruikswijzen = $this->Intake->Verslavingsgebruikswijze->find('list');
        $verslavingen = $this->Intake->Verslaving->find('list');

        $primary_problems = $this->Intake->PrimaireProblematiek->find('list');
        $primaireproblematieksgebruikswijzen = $verslavingsgebruikswijzen;
        $zrm_data = $this->ZrmReport->zrm_data();
        $this->set('diensten', $this->Intake->Klant->diensten($klant_id));

        $this->set(compact('zrm_data', 'primary_problems', 'klant', 'medewerkers',
            'verblijfstatussen', 'legitimaties', 'verslavingsfrequenties',
            'verslavingsperiodes', 'woonsituaties', 'locatie1s', 'locatie2s',
            'locatie3s',
            'inkomens', 'instanties', 'verslavingsgebruikswijzen',
            'verslavingen', 'intaker_id', 'datum_intake',
            'primaireproblematieksgebruikswijzen', 'informele_zorg_mail',
            'dagbesteding_mail', 'inloophuis_mail', 'hulpverlening_mail'
        ));
    }

    public function edit($id = null)
    {
        $this->loadModel('ZrmReport');

        if (!$id && empty($this->data)) {
            $this->flashError(__('Ongeldige intake', true));
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->data)) {
            $this->Intake->begin();

            if ($this->Intake->save($this->data)) {
                $this->ZrmReport->update_zrm_data_for_edit($this->data, 'Intake', $id, $this->data['Intake']['klant_id']);

                if ($this->ZrmReport->save($this->data)) {
                    $this->Intake->commit();
                    $this->sendIntakeNotification($this->Intake->id, $this->data);
                    $this->flash(__('De intake is opgeslagen', true));

                    $this->redirect(array('controller' => 'klanten', 'action' => 'view', $this->data['Intake']['klant_id']));
                }
                $this->flashError(__('De intake is niet opgeslagen. Controleer de rood gemarkeerde invoervelden en probeer opnieuw.', true));
            } else {
                $this->flashError(__('De intake is niet opgeslagen. Controleer de rood gemarkeerde invoervelden en probeer opnieuw.', true));
            }
            $this->Intake->rollback();
        }

        if (empty($this->data)) {
            $this->data = $this->Intake->read(null, $id);

            $dateCreated = new \DateTime($this->data['Intake']['created']);
            if ($dateCreated < new \DateTime('-7 days')) {
                $this->flashError(__('You can only edit intakes that have been created last week.', true));
                $this->redirect(array(
                        'controller' => 'klanten',
                        'action' => 'view',
                        $this->data['Intake']['klant_id'],
                        ));
            }
        }

        $logged_in_user_id = $this->Session->read('Auth.Medewerker.id');
        if ($this->data['Intake']['medewerker_id'] != $logged_in_user_id) {
            $this->flashError(__('You can only edit intakes that you created.', true));
            $this->redirect(array(
                'controller' => 'klanten',
                'action' => 'view',
                $this->data['Intake']['klant_id'],
            ));
        }

        $informele_zorg_mail = Configure::read('informele_zorg_mail');
        $dagbesteding_mail = Configure::read('dagbesteding_mail');
        $inloophuis_mail = Configure::read('inloophuis_mail');
        $hulpverlening_mail = Configure::read('hulpverlening_mail');

        $medewerkers = $this->Intake->Medewerker->find('list');
        $verblijfstatussen = $this->Intake->Verblijfstatus->find('list', array('order' => 'Verblijfstatus.naam ASC'));
        $legitimaties = $this->Intake->Legitimatie->find('list');
        $verslavingsfrequenties = $this->Intake->Verslavingsfrequentie->find('list');
        $verslavingsperiodes = $this->Intake->Verslavingsperiode->find('list');
        $woonsituaties = $this->Intake->Woonsituatie->find('list');

        $locatie1s = $this->Intake->Locatie1->find('list', array('conditions' => array('Locatie1.gebruikersruimte' => 1)));
        $locatie2s = $this->Intake->Locatie2->find('list', array('conditions' => array()));
        $locatie3s = array();

        $inkomens = $this->Intake->Inkomen->find('list');
        $instanties = $this->Intake->Instantie->find('list');
        $verslavingsgebruikswijzen = $this->Intake->Verslavingsgebruikswijze->find('list');
        $verslavingen = $this->Intake->Verslaving->find('list');
        $primary_problems = $this->Intake->PrimaireProblematiek->find('list');
        $primaireproblematieksgebruikswijzen = $verslavingsgebruikswijzen;

        $this->set('diensten', $this->Intake->Klant->diensten($this->data['Intake']['klant_id']));
        $this->set(compact('primary_problems', 'klant', 'medewerkers',
            'verblijfstatussen', 'legitimaties', 'verslavingsfrequenties',
            'verslavingsperiodes', 'woonsituaties', 'locatie1s', 'locatie2s',
            'locatie3s',
            'inkomens', 'instanties', 'verslavingsgebruikswijzen',
            'verslavingen', 'intaker_id', 'datum_intake',
            'primaireproblematieksgebruikswijzen', 'informele_zorg_mail',
            'dagbesteding_mail', 'inloophuis_mail', 'hulpverlening_mail'
        ));

        $this->Intake->Klant->recursive = 1;
        $klant = $this->Intake->Klant->findById($this->data['Intake']['klant_id']);
        $zrm_data = $this->ZrmReport->zrm_data();

        if (empty($this->data['ZrmReport'])) {
            $zrm = $this->ZrmReport->get_zrm_report('Intake', $id, $this->data['Intake']['klant_id']);
            $this->data['ZrmReport'] = $zrm['ZrmReport'];
        }
        $this->set(compact('klant', 'zrm_data'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Ongeldige id voor intake', true));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->Intake->delete($id)) {
            $this->flash(__('Intake verwijderd', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->flashError(__('Intake is niet verwijderd', true));
        $this->redirect(array('action' => 'index'));
    }

    public function sendIntakeNotification($intake_id, $data)
    {
        if (!isset($data['Intake']['informele_zorg']) || !isset($data['Intake']['dagbesteding'])
            || !isset($data['Intake']['inloophuis']) || !isset($data['Intake']['hulpverlening'])
        ) {
            return;
        }

        $addresses = array();
        if ((isset($data['Intake']['informele_zorg_ignore']) &&
            !$data['Intake']['informele_zorg_ignore'] && $data['Intake']['informele_zorg'])
            ||
            (!isset($data['Intake']['informele_zorg_ignore']) &&
            $data['Intake']['informele_zorg'])
        ) {
            $addresses[] = Configure::read('informele_zorg_mail');
        }
        if ((isset($data['Intake']['dagbesteding_ignore']) &&
            !$data['Intake']['dagbesteding_ignore'] && $data['Intake']['dagbesteding'])
            ||
            (!isset($data['Intake']['dagbesteding_ignore']) &&
            $data['Intake']['dagbesteding'])
        ) {
            $addresses[] = Configure::read('dagbesteding_mail');
        }
        if ((isset($data['Intake']['inloophuis_ignore']) &&
            !$data['Intake']['inloophuis_ignore'] && $data['Intake']['inloophuis'])
            ||
            (!isset($data['Intake']['inloophuis_ignore']) &&
            $data['Intake']['inloophuis'])
        ) {
            $addresses[] = Configure::read('inloophuis_mail');
        }
        if ((isset($data['Intake']['hulpverlening_ignore']) &&
            !$data['Intake']['hulpverlening_ignore'] && $data['Intake']['hulpverlening'])
            ||
            (!isset($data['Intake']['hulpverlening_ignore']) &&
            $data['Intake']['hulpverlening'])
        ) {
            $addresses[] = Configure::read('hulpverlening_mail');
        }

        if (count($addresses) > 0) {
            $data['Intake'] = $this->Intake->find('first', array(
                'conditions' => array('Intake.id' => $intake_id),
                'contain' => $this->Intake->contain,
            ));
            $this->_genericSendEmail(array(
                'to' => $addresses,
                'content' => $data['Intake'],
            ));
        }
    }

    /**
     * Prints an empty intake form to let the user fill it in offline.
     */
    public function print_empty()
    {
        $this->set('verblijfstatussen', $this->Intake->Verblijfstatus->find('list', array(
                'order' => 'Verblijfstatus.naam ASC',
        )));
        $this->set('legitimaties', $this->Intake->Legitimatie->find('list'));
        $this->set('problems', $this->Intake->PrimaireProblematiek->find('list'));
        $this->set('verslavingsfrequenties', $this->Intake->Verslavingsfrequentie->find('list'));
        $this->set('verslavingsperiodes', $this->Intake->Verslavingsperiode->find('list'));
        $this->set('verslavingsgebruikswijzen', $this->Intake->Verslavingsgebruikswijze->find('list'));
        $this->set('inkomens', $this->Intake->Inkomen->find('list'));
        $this->set('woonsituaties', $this->Intake->Woonsituatie->find('list'));
        $this->set('instanties', $this->Intake->Instantie->find('list'));
    }
}
