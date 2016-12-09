<?php

class Hi5IntakesController extends AppController
{
    public $name = 'Hi5Intakes';

    public function index()
    {
        $this->Hi5Intake->recursive = 0;
        $this->set('hi5Intakes', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid hi5 intake', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('hi5Intake', $this->Hi5Intake->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Hi5Intake->create();

            if ($this->Hi5Intake->save($this->data)) {
                $this->flash(__('The hi5 intake has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The hi5 intake could not be saved. Please, try again.', true));
            }
        }

        $klanten = $this->Hi5Intake->Klant->find('list');

        $this->setMedewerkers();

        $verblijfstatussen = $this->Hi5Intake->Verblijfstatus->find('list');
        $legitimaties = $this->Hi5Intake->Legitimatie->find('list');

        $primaireProblematieks = $this->Hi5Intake->PrimaireProblematiek->find('list');
        $primaireProblematieksfrequenties = $this->Hi5Intake->PrimaireProblematieksfrequentie->find('list');
        $primaireProblematieksperiodes = $this->Hi5Intake->PrimaireProblematieksperiode->find('list');

        $verslavingsfrequenties = $this->Hi5Intake->Verslavingsfrequentie->find('list');
        $verslavingsperiodes = $this->Hi5Intake->Verslavingsperiode->find('list');

        $woonsituaties = $this->Hi5Intake->Woonsituatie->find('list');

        $locatie1s = $this->Hi5Intake->Locatie1->find('list');
        $locatie2s = $this->Hi5Intake->Locatie2->find('list');

        $werklocaties = $this->Hi5Intake->Werklocatie->find('list');
        $bedrijfitem1s = $this->Hi5Intake->Bedrijfitem1->find('list');
        $bedrijfitem2s = $this->Hi5Intake->Bedrijfitem2->find('list');

        $verslavingsgebruikswijzen = $this->Hi5Intake->Verslavingsgebruikswijze->find('list');
        $primaireproblematieksgebruikswijzen = $this->Hi5Intake->Primaireproblematieksgebruikswijze->find('list');
        $verslavingen = $this->Hi5Intake->Verslaving->find('list');

        $inkomens = $this->Hi5Intake->Inkoman->find('list');

        $instanties = $this->Hi5Intake->Instantie->find('list');

        $hi5Answers = $this->Hi5Intake->Hi5Answer->find('list');

        $this->set(compact('klanten', 'verblijfstatussen', 'legitimaties', 'primaireProblematieks', 'primaireProblematieksfrequenties', 'primaireProblematieksperiodes', 'verslavingsfrequenties', 'verslavingsperiodes', 'woonsituaties', 'locatie1s', 'locatie2s', 'werklocaties', 'bedrijfitem1s', 'bedrijfitem2s', 'verslavingsgebruikswijzen', 'primaireproblematieksgebruikswijzen', 'verslavingen', 'inkomens', 'instanties', 'hi5Answers'));
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid hi5 intake', true));
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->data)) {
            $this->data['Hi5Answer']['Hi5Answer'] = [];
            foreach ($this->data['Hi5Answer']['checkbox'] as $k => $v) {
                if ($v) {
                    $this->data['Hi5Answer']['Hi5Answer'][] = $k;
                }
            }

            if ($this->Hi5Intake->save($this->data)) {
                $this->flash(__('The hi5 intake has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The hi5 intake could not be saved. Please, try again.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->Hi5Intake->read(null, $id);
        }

        $klanten = $this->Hi5Intake->Klant->find('list');

        $this->setMedewerkers($this->data['Hi5Instake']['medewerker_id']);

        $verblijfstatussen = $this->Hi5Intake->Verblijfstatus->find('list');
        $legitimaties = $this->Hi5Intake->Legitimatie->find('list');

        $primaireProblematieks = $this->Hi5Intake->PrimaireProblematiek->find('list');
        $primaireProblematieksfrequenties = $this->Hi5Intake->PrimaireProblematieksfrequentie->find('list');
        $primaireProblematieksperiodes = $this->Hi5Intake->PrimaireProblematieksperiode->find('list');

        $verslavingsfrequenties = $this->Hi5Intake->Verslavingsfrequentie->find('list');
        $verslavingsperiodes = $this->Hi5Intake->Verslavingsperiode->find('list');

        $woonsituaties = $this->Hi5Intake->Woonsituatie->find('list');
        $locatie1s = $this->Hi5Intake->Locatie1->find('list');
        $locatie2s = $this->Hi5Intake->Locatie2->find('list');
        $werklocaties = $this->Hi5Intake->Werklocatie->find('list');

        $bedrijfitem1s = $this->Hi5Intake->Bedrijfitem1->find('list');
        $bedrijfitem2s = $this->Hi5Intake->Bedrijfitem2->find('list');

        $verslavingsgebruikswijzen = $this->Hi5Intake->Verslavingsgebruikswijze->find('list');
        $primaireproblematieksgebruikswijzen = $this->Hi5Intake->Primaireproblematieksgebruikswijze->find('list');
        $verslavingen = $this->Hi5Intake->Verslaving->find('list');

        $inkomens = $this->Hi5Intake->Inkomen->find('list');

        $instanties = $this->Hi5Intake->Instantie->find('list');

        $hi5Answers = $this->Hi5Intake->Hi5Answer->find('list');

        $this->set(compact('klanten', 'verblijfstatussen', 'legitimaties', 'primaireProblematieks', 'primaireProblematieksfrequenties', 'primaireProblematieksperiodes', 'verslavingsfrequenties', 'verslavingsperiodes', 'woonsituaties', 'locatie1s', 'locatie2s', 'werklocaties', 'bedrijfitem1s', 'bedrijfitem2s', 'verslavingsgebruikswijzen', 'primaireproblematieksgebruikswijzen', 'verslavingen', 'inkomens', 'instanties', 'hi5Answers'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for hi5 intake', true));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->Hi5Intake->delete($id)) {
            $this->flashError(__('Hi5 intake deleted', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->flashError(__('Hi5 intake was not deleted', true));

        $this->redirect(array('action' => 'index'));
    }
}
