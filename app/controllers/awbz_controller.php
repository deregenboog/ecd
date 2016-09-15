<?php

class AwbzController extends AppController
{
	public $name = 'Awbz';
	public $uses = array('Klant');
	public $components = array('Filter', 'RequestHandler', 'Session');

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
			$this->redirect(array('action' => 'index'));
		}

		$this->Klant->recursive = -1;
		
		$contain = array(
			'Geslacht',
			'Geboorteland' => array('fields' => 'land'),
			'Nationaliteit' => array('fields' => 'naam'),
			'Medewerker',
			'AwbzIntake',
			'AwbzIndicatie' => array(
				'Hoofdaannemer',
			),
			'AwbzHoofdaannemer' => array('Hoofdaannemer'),

		);
		
		$klant = $this->Klant->find('first', array(
			'conditions' => array('Klant.id' => $klant_id),
			'contain' => $contain,
		));
		
		$hoofdaannemers =
			$this->Klant->AwbzHoofdaannemer->Hoofdaannemer->find('list');

		$intake_type = 'awbz';

		$this->set(compact('klant', 'hoofdaannemers', 'intake_type'));
		
	}

	public function zrm($id = null)
	{
		$this->loadModel('ZrmReport');
		
		if (!$id) {
			$this->flashError(__('Invalid klant', true));
			$this->redirect(array('action' => 'index'));
		}

		$klant = $this->Klant->read(null, $id);
		$this->set('klant', $klant);

		$zrmReports = $this->ZrmReport->find('all', array(
				'conditions' => array('klant_id' => $id),
				'order' => 'created DESC',
		));
		
		$zrm_data = $this->ZrmReport->zrm_data();

		$this->set('zrmReports', $zrmReports);
		$this->set('klant_id', $id);
		$this->set('zrm_data', $zrm_data);
	}

	public function rapportage()
	{
		$showForm = false;
		
		if (empty($this->data)) {
			$showForm = true;
		} else {
			
			$reportData = $this->Klant->AwbzIndicatie->getAbwzReportData(
				(int)$this->data['year']['year'],
				(int)$this->data['month']['month']
			);
			
		}
		
		$this->set(compact('reportData', 'showForm'));
	}
}
