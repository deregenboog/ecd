<?php

class ZrmReportsController extends AppController
{
	public $name = 'ZrmReports';

	public function index()
	{
		$this->ZrmReport->recursive = 0;
		$this->set('zrmReports', $this->paginate());
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid zrm report', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('zrm_data', $this->ZrmReport->zrm_data());
		$this->set('zrmReport', $this->ZrmReport->read(null, $id));
	}

	public function add()
	{
		if (!empty($this->data)) {
			//debug($this->params); debug($this->data); die('dddd');

			$this->ZrmReport->create();
			if ($this->ZrmReport->save($this->data)) {
				$this->Session->setFlash(__('The zrm report has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The zrm report could not be saved. Please, try again.', true));
			}
		}
		$this->set('zrm_data', $this->ZrmReport->zrm_data());
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid zrm report', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ZrmReport->save($this->data)) {
				$this->Session->setFlash(__('The zrm report has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The zrm report could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ZrmReport->read(null, $id);
		}
		$this->set('zrm_data', $this->ZrmReport->zrm_data());
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for zrm report', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ZrmReport->delete($id)) {
			$this->Session->setFlash(__('Zrm report deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Zrm report was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
