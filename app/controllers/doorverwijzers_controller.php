<?php

class DoorverwijzersController extends AppController
{
	public $name = 'Doorverwijzers';

	public function index()
	{
		$this->Doorverwijzer->recursive = 0;
		$this->set('doorverwijzers', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid doorverwijzer', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('doorverwijzer', $this->Doorverwijzer->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			$this->Doorverwijzer->create();
			if ($this->Doorverwijzer->save($this->data)) {
				$this->flashError(__('The doorverwijzer has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The doorverwijzer could not be saved. Please, try again.', true));
			}
		}
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->flashError(__('Invalid doorverwijzer', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Doorverwijzer->save($this->data)) {
				$this->flashError(__('The doorverwijzer has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The doorverwijzer could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Doorverwijzer->read(null, $id);
		}
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid id for doorverwijzer', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Doorverwijzer->delete($id)) {
			$this->flashError(__('Doorverwijzer deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->flashError(__('Doorverwijzer was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
