<?php

class IzVrijwilligersController extends AppController
{
    const REPORT_BEGINSTAND = 'beginstand';
    const REPORT_GESTART = 'gestart';
    const REPORT_AFGESLOTEN = 'afgesloten';
    const REPORT_EINDSTAND = 'eindstand';

    public $helpers = ['Medewerker', 'Project'];

    public function index()
    {
        $filterApplied = $this->applyFilter();
        if ($filterApplied) {
            $conditions = ['IzVrijwilliger.id' => $this->IzVrijwilliger->getFilteredIds($this->data)];
        } else {
            $conditions = [];
        }

        $this->set('medewerkers', $this->getMedewerkers());
        $this->set('projecten', $this->getIzProjecten());
        $this->set('werkgebieden', Configure::read('Werkgebieden') + ['null' => 'onbekend']);

        if (@$this->params['named']['format'] === self::FORMAT_CSV) {
            $this->set('delimiter', ';');
            $this->set('personen', $this->IzVrijwilliger->find('all', [
                'conditions' => $conditions,
            ]));

            $filename = 'iz_vrijwilligers-'.implode('-', $this->data['Query']).'.csv';

            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'"');

            return $this->render('index.csv', false);
        }

        $this->paginate = ['IzVrijwilliger' => ['conditions' => $conditions]];

        $this->set('personen', $this->paginate());
    }

    private function getMedewerkers()
    {
        $this->loadModel('Medewerker');

        return $this->Medewerker->find('list', array(
            'joins' => array(
                array(
                    'table' => 'iz_koppelingen',
                    'alias' => 'IzHulpaanbod',
                    'type' => 'INNER',
                    'conditions' => array(
                        'IzHulpaanbod.medewerker_id = Medewerker.id',
                    ),
                ),
            ),
        ));
    }

    private function getIzProjecten()
    {
        $this->loadModel('IzProject');

        return $this->IzProject->find('list', array(
            'joins' => array(
                array(
                    'table' => 'iz_koppelingen',
                    'alias' => 'IzHulpaanbod',
                    'type' => 'INNER',
                    'conditions' => array(
                        'IzHulpaanbod.project_id = IzProject.id',
                    ),
                ),
            ),
        ));
    }
}
