<?php

class IntakesController extends AppController
{
	public $name = 'Intakes';
	public $components = array('Intakez' => array(
		'module' => 'Registratie',
	));

	public function view($id = null)
	{
		if ($this->Intakez->view($id)) {
			$this->render('/intakes/view');
		} else {
			$this->redirect(array(
				'controller' => 'klanten',
				'action' => 'index',
			));
		}
	}

	public function add($klant_id = null)
	{
		$redirect_url = array(
			'controller' => 'klanten',
			'action' => 'view',
			$klant_id,
		);
		if (!empty($this->data)) {
			if ($this->Intakez->save_data($klant_id)) { //saved successfully
				//save the last intake field in the klant:
				$this->Intake->set_last_intake($klant_id);
				$this->redirect($redirect_url);
			} else {
				//something went wrong, render the same view again with validation
			//errors
				$this->Intakez->setup_add_view($klant_id);
				//no redirect
			}
		} else {
			if (!$this->Intakez->setup_add_view($klant_id)) {
				//flash messages are already set by the compontent
				$this->redirect(array(
					'controller' => 'klanten',
					'action' => 'index',
				));
			}
		}

		$this->set('back_action', $redirect_url);
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->c->flashError(__('Ongeldige intake', true));
			$this->c->redirect(array(
				'controller' => 'klanten',
				'action' => 'index',
			));
		}

		$klant_view = array('controller' => 'klanten', 'action' => 'view');

		if (!empty($this->data)) {
			if ($this->Intakez->save_edited()) {
				$this->redirect(
					$klant_view + array($this->data['Intake']['klant_id'])
				);
			}
			//on save failure:
			$this->Intakez->setup_edit_view($this->data['Intake']['id']);
		} else {
			if (!$this->Intakez->setup_edit_view($id)) {
				if ($this->data['Intake']['klant_id']) {
					$this->redirect(
						$klant_view + array($this->data['Intake']['klant_id'])
					);
				} else {
					$this->redirect(array(
						'controller' => 'klanten',
						'action' => 'index',
					));
				}
			}
		}//end of else (for empty this->data)
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->flashError(__('Ongeldige id voor intake', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Intake->delete($id)) {
			$this->flashError(__('Intake verwijderd', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->flashError(__('Intake is niet verwijderd', true));
		$this->redirect(array('action' => 'index'));
	}
}
