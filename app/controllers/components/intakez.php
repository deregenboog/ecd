<?php

class IntakezComponent extends Object
{
    public $module = null;

    public function initialize(&$controller, $settings = array())
    {
        $defaults = array(
            'module' => 'Registratie',
        );

        $settings = $settings + $defaults;

        $this->module = $settings['module'];

        $this->c = $controller;
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->c->flashError(__('Invalid intake', true));

            return false;
        }

        $intake = $this->c->Intake->find('first', array(
            'conditions' => array(
                'Intake.id' => $id,
                'Intake.module' => $this->module,
            ),
        ));

        if (empty($intake)) {
            $this->c->flashError(__('Invalid intake', true));

            return false;
        }

        $klant = $this->c->Intake->Klant->read(null, $intake['Klant']['id']);

        App::import('Helper', 'Date');
        $dateHelper = new DateHelper();

        $title_for_layout = ' - Intake van '.
            $intake['Klant']['name'].' op '.
            $dateHelper->show($intake['Intake']['datum_intake']);

        $this->c->set(compact('title_for_layout', 'intake', 'klant'));

        return true;
    }

    public function save_data($klant_id)
    {
        $this->c->Intake->create();
        $this->c->data['Intake']['module'] = $this->module;

        if ($this->c->Intake->save($this->c->data)) {
            $this->sendIntakeNotification(
                $this->c->Intake->id, $this->c->data);
            $this->c->flash(
                __('De intake is opgeslagen', true));

            return true;
        } else {
            $this->c->flashError(
                __('De intake is niet opgeslagen. Controleer de rood'.
                ' gemarkeerde invoervelden en probeer opnieuw.', true)
            );

            return false;
        }
    }

    public function setup_add_view($klant_id)
    {
        if ($klant_id == null) {
            $this->c->flashError('Geen klant Id opgegeven');

            return false;
        }
        $klant = $this->c->Intake->Klant->read(null, $klant_id);

        if (empty($klant)) {
            $this->c->flashError('Geen klant Id opgegeven');

            return false;
        }

        if (empty($this->c->data)) {
            $current = $this->populate_intake_data($klant_id);

            $datum_intake = date('Y-m-d');

            $intaker_id = $this->c->Session->read('Auth.Medewerker.id');
        } else {
            $datum_intake = null;

            $intaker_id = $this->c->data['Intake']['medewerker_id'];
        }

        $this->set_form_data();
        $this->c->set(compact('klant', 'intaker_id', 'datum_intake'));

        return true;
    }

    public function setup_edit_view($id = null)
    {
        if ($this->c->data) {
            $intaker_id = $this->c->data['Intake']['medewerker_id'];
        } else {
            $this->c->data = $this->c->Intake->find('first', array(
                'conditions' => array(
                    'Intake.id' => $id,
                    'Intake.module' => $this->module,
                ),
            ));

            if (empty($this->c->data)) {
                $this->c->flashError(__('Ongeldige intake', true));

                return false;
            }

            if (!$this->can_edit()) {
                return false;
            }

            $intaker_id = $this->c->Session->read('Auth.Medewerker.id');
        }
        $this->set_form_data();

        $klant = $this->c->Intake->Klant->findById($this->c->data['Intake']['klant_id']);
        $this->c->set(compact('klant', 'intaker_id'));

        return true;
    }

    public function set_form_data()
    {
        $informele_zorg_mail = Configure::read('informele_zorg_mail');
        $dagbesteding_mail = Configure::read('dagbesteding_mail');
        $inloophuis_mail = Configure::read('inloophuis_mail');
        $hulpverlening_mail = Configure::read('hulpverlening_mail');

        $medewerkers = $this->c->Intake->Medewerker->find('list');
        $verblijfstatussen = $this->c->Intake->Verblijfstatus->find('list', array('order' => 'Verblijfstatus.naam ASC'));
        $legitimaties = $this->c->Intake->Legitimatie->find('list');
        $verslavingsfrequenties = $this->c->Intake->Verslavingsfrequentie->find('list');
        $verslavingsperiodes = $this->c->Intake->Verslavingsperiode->find('list');
        $woonsituaties = $this->c->Intake->Woonsituatie->find('list');
        $locatie1s = $this->c->Intake->Locatie1->find('list');
        $locatie2s = &$locatie1s;
        $inkomens = $this->c->Intake->Inkomen->find('list');
        $instanties = $this->c->Intake->Instantie->find('list');
        $verslavingsgebruikswijzen = $this->c->Intake->Verslavingsgebruikswijze->find('list');
        $verslavingen = $this->c->Intake->Verslaving->find('list');
        $primary_problems = $this->c->Intake->PrimaireProblematiek->find('list');
        $primaireproblematieksgebruikswijzen = $verslavingsgebruikswijzen;

        $this->c->set(compact('primary_problems', 'medewerkers',
            'verblijfstatussen', 'legitimaties', 'verslavingsfrequenties',
            'verslavingsperiodes', 'woonsituaties', 'locatie1s', 'locatie2s',
            'inkomens', 'instanties', 'verslavingsgebruikswijzen',
            'verslavingen', 'primaireproblematieksgebruikswijzen',
            'informele_zorg_mail', 'dagbesteding_mail', 'inloophuis_mail',
            'hulpverlening_mail'
        ));
    }

    public function save_edited($id = null)
    {
        if (!empty($this->c->data)) {
            if ($this->c->Intake->save($this->c->data)) {
                $this->sendIntakeNotification($this->c->Intake->id, $this->c->data);
                $this->c->flash(__('De intake is opgeslagen', true));

                return true;
            } else {
                $this->c->flashError(__('De intake is niet opgeslagen. Controleer de rood gemarkeerde invoervelden en probeer opnieuw.', true));

                return false;
            }
        }

        $klant = $this->c->Intake->Klant->findById($this->c->data['Intake']['klant_id']);

        $this->c->set(compact('klant', 'intaker_id', 'datum_intake'));

        $this->c->render('/intakes/edit');
    }

    /*
     * checks whether it's allowed to edit the intake
     * needs to have the controller->data set
     */

    public function can_edit()
    {
        //it's not allowed to edit intakes other than submitted today
        if ($this->c->data['Intake']['datum_intake'] != date('Y-m-d')) {
            $this->c->flashError(__(
                'You can only edit intakes that have been created today.',
                true
            ));

            return false;
        }

    //it's not allowed to edit intakes that somebody else submitted
        $logged_in_user_id = $this->c->Session->read('Auth.Medewerker.id');
        if ($this->c->data['Intake']['medewerker_id'] != $logged_in_user_id) {
            $this->c->flashError(__(
                'You can only edit intakes that you created.',
                true
            ));

            return false;
        }

        return true;
    }

    /*
     * sends notification emails on intake submision or editing;
     *
     * fields like: $data['Intake']['informele_zorg_ignore'] etc. (ending with
     * _ignore) say that the intake had a 1 on that field originally so if it's
     * 1 now no email is sent because there was no change
    */
    public function sendIntakeNotification($intake_id, &$data)
    {
        $intake = &$data['Intake'];

        //If the fields are not set this means the function has been disabled
        //and the browser didn't send this data, in which case return.
        if (!isset($intake['informele_zorg']) || !isset($intake['dagbesteding'])
            || !isset($intake['inloophuis']) || !isset($intake['hulpverlening'])
        ) {
            return;
        }

        //checking which addresses we should sent the mails to
        $addresses = array();
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

        //if some of the above conditions was passed - send e-mail
        if (count($addresses) > 0) {
            //getting the intake details
            $intake = $this->c->Intake->find('first', array(
                'conditions' => array('Intake.id' => $intake_id),
                'contain' => $this->c->Intake->contain,
            ));
            //sending e-mail
            $this->c->_genericSendEmail(array(
                'to' => $addresses,
                'content' => $intake,
            ));
        }
    }//sendIntakeNotification()

    public function populate_intake_data($klant_id)
    {

    //most recent intake
        $last_intake = $this->c->Intake->find('first', array(
            'conditions' => array('Intake.klant_id' => $klant_id),
            'order' => 'Intake.datum_intake DESC, Intake.created DESC',
        ));

        if (empty($last_intake)) {
            return false;
        }
        //debug($last_intake);
    //if last intake is of the same kind, we set it whole to form data
        if ($last_intake['Intake']['module'] == $this->module) {
            $this->c->data = &$last_intake;

    //if not - copy only the address info from it, and get the rest
    //from the last intake of the same kind (providing it exists)
        } else {
            $last_same_type_intake = $this->c->Intake->find('first', array(
                'conditions' => array(
                    'Intake.klant_id' => $klant_id,
                    'Intake.module' => $this->module,
                ),
                'order' => 'Intake.datum_intake DESC, Intake.created DESC',
            ));
            debug($last_same_type_intake);
            if (!empty($last_same_type_intake)) {
                $this->c->data = &$last_same_type_intake;
            } else {
                $this->c->data = array('Intake' => array());
            }

            foreach (array('postadres', 'postcode', 'woonplaats',
                'verblijfstatus_id', 'verblijf_in_NL_sinds',
                'verblijf_in_amsterdam_sinds', ) as $field
            ) {
                $this->c->data['Intake'][$field] =
                    $last_intake['Intake'][$field];
            }
        }

        $intaker_id = $this->c->Session->read('Auth.Medewerker.id');
        $this->c->data['Intake']['medewerker_id'] = $intaker_id;
        //need to unset the intake id so that we don't write the intake over any
        //of the previous ones
        if (isset($this->c->data['Intake']['id'])) {
            unset($this->c->data['Intake']['id']);
        }
    }
}
