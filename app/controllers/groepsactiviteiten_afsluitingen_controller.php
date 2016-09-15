<?php

class GroepsactiviteitenAfsluitingenController extends AppController
{
	public $name = 'GroepsactiviteitenAfsluitingen';

	public function index()
	{
		$this->GroepsactiviteitenAfsluiting->recursive = 0;
		$this->set('groepsactiviteitenAfsluitingen', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid groepsactiviteiten afsluiting', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('groepsactiviteitenAfsluiting', $this->GroepsactiviteitenAfsluiting->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			$this->GroepsactiviteitenAfsluiting->create();
			if ($this->GroepsactiviteitenAfsluiting->save($this->data)) {
				$this->Session->setFlash(__('The groepsactiviteiten afsluiting has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The groepsactiviteiten afsluiting could not be saved. Please, try again.', true));
			}
		}
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid groepsactiviteiten afsluiting', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->GroepsactiviteitenAfsluiting->save($this->data)) {
				$this->Session->setFlash(__('The groepsactiviteiten afsluiting has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The groepsactiviteiten afsluiting could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->GroepsactiviteitenAfsluiting->read(null, $id);
		}
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for groepsactiviteiten afsluiting', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->GroepsactiviteitenAfsluiting->delete($id)) {
			$this->Session->setFlash(__('Groepsactiviteiten afsluiting deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Groepsactiviteiten afsluiting was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
