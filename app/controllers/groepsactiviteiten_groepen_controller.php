<?php

class GroepsactiviteitenGroepenController extends AppController
{
    public $name = 'GroepsactiviteitenGroepen';

    public function index($showall = false)
    {
        $this->GroepsactiviteitenGroep->recursive = 0;

        $conditions = [
            'OR' => [
                'einddatum ' => null,
                'einddatum > ' => date('Y-m-d'),
            ],
        ];

        if (!empty($showall)) {
            $conditions = [
               'einddatum <= ' => date('Y-m-d'),
            ];
        }

        $this->paginate = [
            'conditions' => $conditions,
        ];

        $this->set('groepsactiviteitenGroepen', $this->paginate());
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->GroepsactiviteitenGroep->create();

            if ($this->GroepsactiviteitenGroep->save($this->data)) {
                $this->Session->setFlash(__('De groep is opgeslagen', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash(__('Groep kan niet worden opgeslagen', true));
            }
        }

        $this->set('werkgebieden', Configure::read('Werkgebieden'));
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Niet geldige groep', true));
            $this->redirect(['action' => 'index']);
        }

        if (!empty($this->data)) {
            $this->data['GroepsactiviteitenGroep']['id'] = $id;

            if ($this->GroepsactiviteitenGroep->save($this->data)) {
                $this->Session->setFlash(__('De groep is opgeslagen', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash(__('Groep kan niet worden opgeslagen', true));
            }
        }
        if (empty($this->data)) {
            $this->GroepsactiviteitenGroep->recursive = 0;
            $this->data = $this->GroepsactiviteitenGroep->read(null, $id);
        }

        $this->set('werkgebieden', Configure::read('Werkgebieden'));
    }

    public function export($id, $persoon_model = 'Klant')
    {
        $this->autoLayout = false;
        $this->layout = false;

        $model = $this->name.$persoon_model;

        $groepsactiviteiten_list = $this->GroepsactiviteitenGroep->get_groepsactiviteiten_list();
        $groep = $groepsactiviteiten_list[$id];

        $this->loadModel($model);

        $params = [
            'contain' => [$persoon_model => ['GroepsactiviteitenIntake']],
            'conditions' => [
                'groepsactiviteiten_groep_id' => $id,
                'OR' => [
                    'einddatum > now()',
                    'einddatum' => null,
                ],
            ],
        ];

        $members = $this->{$model}->find('all', $params);

        $this->set('groep', $groep);
        $this->set('members', $members);
        $this->set('model', $model);
        $this->set('persoon_model', $persoon_model);

        $file = "{$groep}_{$persoon_model}_lijst.xls";

        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$file\";");
        header('Content-Transfer-Encoding: binary');

        $this->render('groep_excel');
    }
}
