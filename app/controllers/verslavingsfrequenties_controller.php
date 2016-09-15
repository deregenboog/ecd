<?php

class VerslavingsfrequentiesController extends AppController
{
	public $name = 'Verslavingsfrequenties';

	public function index()
	{
		$this->Verslavingsfrequentie->recursive = 0;
		$this->set('verslavingsfrequenties', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid verslavingsfrequentie', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('verslavingsfrequentie', $this->Verslavingsfrequentie->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			$this->Verslavingsfrequentie->create();
			if ($this->Verslavingsfrequentie->save($this->data)) {
				$this->flashError(__('The verslavingsfrequentie has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The verslavingsfrequentie could not be saved. Please, try again.', true));
			}
		}
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->flashError(__('Invalid verslavingsfrequentie', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Verslavingsfrequentie->save($this->data)) {
				$this->flashError(__('The verslavingsfrequentie has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The verslavingsfrequentie could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Verslavingsfrequentie->read(null, $id);
		}
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid id for verslavingsfrequentie', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Verslavingsfrequentie->delete($id)) {
			$this->flashError(__('Verslavingsfrequentie deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->flashError(__('Verslavingsfrequentie was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
