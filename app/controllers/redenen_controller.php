<?php

class RedenenController extends AppController
{
	public $name = 'Redenen';

	public function index()
	{
		$this->Reden->recursive = 0;
		$this->set('redenen', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid reden', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('reden', $this->Reden->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			$this->Reden->create();
			if ($this->Reden->save($this->data)) {
				$this->flashError(__('The reden has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The reden could not be saved. Please, try again.', true));
			}
		}
		$schorsingen = $this->Reden->Schorsing->find('list');
		$this->set(compact('schorsingen'));
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->flashError(__('Invalid reden', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Reden->save($this->data)) {
				$this->flashError(__('The reden has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The reden could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Reden->read(null, $id);
		}
		$schorsingen = $this->Reden->Schorsing->find('list');
		$this->set(compact('schorsingen'));
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid id for reden', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Reden->delete($id)) {
			$this->flashError(__('Reden deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->flashError(__('Reden was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
