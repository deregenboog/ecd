<?php

class AwbzController extends AppController
{
    public $name = 'Awbz';
    public $uses = ['Klant'];
    public $components = ['Filter', 'RequestHandler', 'Session'];

    public function index()
    {
        if (isset($this->params['named'])) {
            if (isset($this->params['named']['showDisabled'])) {
                $this->Klant->showDisabled = $this->params['named']['showDisabled'];
            }
        }

        $klanten = $this->paginate('Klant', $this->Filter->filterData);

        $this->set(compact('klanten'));

        if ($this->RequestHandler->isAjax()) {
            $this->render('/elements/awbz_klantenlijst', 'ajax');
        }
    }

    public function view($klant_id = null)
    {
        if (!$klant_id) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(['action' => 'index']);
        }

        $this->Klant->recursive = -1;

        $contain = [
            'Geslacht',
            'Geboorteland' => ['fields' => 'land'],
            'Nationaliteit' => ['fields' => 'naam'],
            'Medewerker',
            'AwbzIntake',
            'AwbzIndicatie' => ['Hoofdaannemer'],
            'AwbzHoofdaannemer' => ['Hoofdaannemer'],
        ];

        $klant = $this->Klant->find('first', [
            'conditions' => ['Klant.id' => $klant_id],
            'contain' => $contain,
        ]);

        $hoofdaannemers =
            $this->Klant->AwbzHoofdaannemer->Hoofdaannemer->find('list');

        $intake_type = 'awbz';

        $this->set(compact('klant', 'hoofdaannemers', 'intake_type'));
    }

    public function zrm($id)
    {
        if (!$id) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(['action' => 'index']);
        }

        $klant = $this->Klant->read(null, $id);
        $this->set('klant', $klant);

        $zrmReports = [];
        $zrmData = [];

        $this->loadModel(ZrmReport::class);
        foreach (ZrmReport::getZrmReportModels() as $model) {
            $this->loadModel($model);
            $zrmReports[$model] = $this->{$model}->find('all', [
                'conditions' => ['klant_id' => $id],
                'order' => $model.'.created DESC',
            ]);
            $zrmData[$model] = $this->{$model}->zrm_data();
        }

        $this->set('zrmReports', $zrmReports);
        $this->set('zrmData', $zrmData);
    }

    public function rapportage()
    {
        $showForm = false;

        if (empty($this->data)) {
            $showForm = true;
        } else {
            $reportData = $this->Klant->AwbzIndicatie->getAbwzReportData(
                (int) $this->data['year']['year'],
                (int) $this->data['month']['month']
            );
        }

        $this->set(compact('reportData', 'showForm'));
    }
}
