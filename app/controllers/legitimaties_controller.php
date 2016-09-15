<?php

class LegitimatiesController extends AppController
{
	public $name = 'Legitimaties';

	public function index()
	{
		$this->Legitimatie->recursive = 0;
		$this->set('legitimaties', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid legitimatie', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('legitimatie', $this->Legitimatie->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			$this->Legitimatie->create();
			if ($this->Legitimatie->save($this->data)) {
				$this->flashError(__('The legitimatie has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The legitimatie could not be saved. Please, try again.', true));
			}
		}
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->flashError(__('Invalid legitimatie', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Legitimatie->save($this->data)) {
				$this->flashError(__('The legitimatie has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The legitimatie could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Legitimatie->read(null, $id);
		}
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid id for legitimatie', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Legitimatie->delete($id)) {
			$this->flashError(__('Legitimatie deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->flashError(__('Legitimatie was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
