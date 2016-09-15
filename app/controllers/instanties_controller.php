<?php

class InstantiesController extends AppController
{
	public $name = 'Instanties';

	public function index()
	{
		$this->Instantie->recursive = 0;
		$this->set('instanties', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid instantie', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('instantie', $this->Instantie->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			$this->Instantie->create();
			if ($this->Instantie->save($this->data)) {
				$this->flashError(__('The instantie has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The instantie could not be saved. Please, try again.', true));
			}
		}
		$intakes = $this->Instantie->Intake->find('list');
		$this->set(compact('intakes'));
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->flashError(__('Invalid instantie', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Instantie->save($this->data)) {
				$this->flashError(__('The instantie has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The instantie could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Instantie->read(null, $id);
		}
		$intakes = $this->Instantie->Intake->find('list');
		$this->set(compact('intakes'));
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid id for instantie', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Instantie->delete($id)) {
			$this->flashError(__('Instantie deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->flashError(__('Instantie was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
