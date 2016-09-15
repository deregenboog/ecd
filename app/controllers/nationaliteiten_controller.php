<?php

class NationaliteitenController extends AppController
{
	public $name = 'Nationaliteiten';

	public function index()
	{
		$this->Nationaliteit->recursive = 0;
		$this->set('nationaliteiten', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid nationaliteit', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('nationaliteit', $this->Nationaliteit->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			$this->Nationaliteit->create();
			if ($this->Nationaliteit->save($this->data)) {
				$this->flashError(__('The nationaliteit has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The nationaliteit could not be saved. Please, try again.', true));
			}
		}
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->flashError(__('Invalid nationaliteit', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Nationaliteit->save($this->data)) {
				$this->flashError(__('The nationaliteit has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The nationaliteit could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Nationaliteit->read(null, $id);
		}
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->flashError(__('Invalid id for nationaliteit', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Nationaliteit->delete($id)) {
			$this->flashError(__('Nationaliteit deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->flashError(__('Nationaliteit was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
