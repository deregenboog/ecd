<?php

class IzVraagaanbodenController extends AppController
{
	public $name = 'IzVraagaanboden';

	public function index()
	{
		$this->IzVraagaanbod->recursive = 0;
		$this->set('izVraagaanboden', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid iz vraagaanbod', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('izVraagaanbod', $this->IzVraagaanbod->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			
			$this->IzVraagaanbod->create();
			
			if ($this->IzVraagaanbod->save($this->data)) {
				$this->Session->setFlash(__('The iz vraagaanbod has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The iz vraagaanbod could not be saved. Please, try again.', true));
			}
			
		}
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid iz vraagaanbod', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			
			if ($this->IzVraagaanbod->save($this->data)) {
				
				$this->Session->setFlash(__('The iz vraagaanbod has been saved', true));
				$this->redirect(array('action' => 'index'));
				
			} else {
				$this->Session->setFlash(__('The iz vraagaanbod could not be saved. Please, try again.', true));
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->IzVraagaanbod->read(null, $id);
		}
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for iz vraagaanbod', true));
			$this->redirect(array('action'=>'index'));
		}
		
		if ($this->IzVraagaanbod->delete($id)) {
			
			$this->Session->setFlash(__('Iz vraagaanbod deleted', true));
			$this->redirect(array('action'=>'index'));
			
		}
		
		$this->Session->setFlash(__('Iz vraagaanbod was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
