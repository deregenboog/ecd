<?php

class PfoGroepenController extends AppController
{
	public $name = 'PfoGroepen';

	public function index()
	{
		$this->PfoGroep->recursive = 0;
		$this->set('pfoGroepen', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid pfo groep', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('pfoGroep', $this->PfoGroep->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			$this->PfoGroep->create();
			if ($this->PfoGroep->save($this->data)) {
				$this->Session->setFlash(__('The pfo groep has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The pfo groep could not be saved. Please, try again.', true));
			}
		}
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid pfo groep', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->PfoGroep->save($this->data)) {
				$this->Session->setFlash(__('The pfo groep has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The pfo groep could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->PfoGroep->read(null, $id);
		}
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for pfo groep', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->PfoGroep->delete($id)) {
			$this->Session->setFlash(__('Pfo groep deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Pfo groep was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
