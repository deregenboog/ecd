<?php

class WoonsituatiesController extends AppController
{
	public $name = 'Woonsituaties';

	public function index()
	{
		$this->Woonsituatie->recursive = 0;
		$this->set('woonsituaties', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid woonsituatie', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('woonsituatie', $this->Woonsituatie->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			$this->Woonsituatie->create();
			if ($this->Woonsituatie->save($this->data)) {
				$this->flashError(__('The woonsituatie has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The woonsituatie could not be saved. Please, try again.', true));
			}
		}
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->flashError(__('Invalid woonsituatie', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Woonsituatie->save($this->data)) {
				$this->flashError(__('The woonsituatie has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The woonsituatie could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Woonsituatie->read(null, $id);
		}
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid id for woonsituatie', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Woonsituatie->delete($id)) {
			$this->flashError(__('Woonsituatie deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->flashError(__('Woonsituatie was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
