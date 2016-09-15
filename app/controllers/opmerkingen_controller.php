<?php

class OpmerkingenController extends AppController
{
	public $name = 'Opmerkingen';

	public function index($klant_id = null, $locatie_id = null)
	{
		if ($locatie_id) {
			$this->set(compact('locatie_id'));
		}
		if ($klant_id === null) {
			
			$this->flashError(__('Invalid klant', true));
			$this->redirect(array('controller' => 'registraties',
				'action' => 'index', ));
			
		}
		
		$klant = $this->Opmerking->Klant->findById($klant_id);
		
		$this->Opmerking->recursive = 0;
		
		$opmerkingen = $this->Opmerking->find('all', array(
			'conditions' => array('Opmerking.klant_id' => $klant_id),
		));
		
		$this->set('diensten', $this->Opmerking->Klant->diensten($klant_id));
		$this->set(compact('opmerkingen', 'klant'));
	}

	public function view($id = null)
	{
		if (!$id) {
			
			$this->flashError(__('Invalid opmerking', true));
			$this->redirect(array('action' => 'index'));
			
		}
		$this->set('opmerking', $this->Opmerking->read(null, $id));
	}

	public function add($klant_id)
	{

		if (!empty($this->data)) {
			
			$this->Opmerking->create();
			
			if ($this->Opmerking->save($this->data)) {
				
				$this->flash(__('The opmerking has been saved', true));
				$this->redirect(array('action' => 'index', $klant_id));
				
			} else {
				$this->flashError(__('The opmerking could not be saved. Please, try again.', true));
			}
		}

		$categorieen = $this->Opmerking->Categorie->find('list');
		
		$this->Opmerking->Klant->recursive = 1;
		
		$klant = $this->Opmerking->Klant->findById($klant_id);

		$this->set('diensten', $this->Opmerking->Klant->diensten($klant_id));
		$this->set(compact(array('categorieen', 'klant')));
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			
			$this->flashError(__('Invalid opmerking', true));
			$this->redirect(array('action' => 'index'));
			
		}
		
		if (!empty($this->data)) {
			
			if ($this->Opmerking->save($this->data)) {
				
				$this->flash(__('The opmerking has been saved', true));
				$this->redirect(array('action' => 'index'));
				
			} else {
				$this->flashError(__('The opmerking could not be saved. Please, try again.', true));
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->Opmerking->read(null, $id);
		}
		
		$klanten = $this->Opmerking->Klant->find('list');
		$categorieen = $this->Opmerking->Categorie->find('list');
		
		$this->set(compact('klanten', 'categorieen'));
	}

	public function delete($id = null, $klant_id = null)
	{
		if (!$id) {
			
			$this->flashError(__('Invalid id for opmerking', true));
			$this->redirect(array('action'=>'index', $klant_id));
			
		}
		
		if ($this->Opmerking->delete($id)) {
			
			$this->flash(__('Opmerking deleted', true));
			$this->redirect(array('action'=>'index', $klant_id));
			
		}
		
		$this->flashError(__('Opmerking was not deleted', true));
		$this->redirect(array('action' => 'index', $klant_id));
	}

	public function gezien($id)
	{
		
		$this->autoRender = false;
		echo $this->Opmerking->gezien($id);

	}
}
