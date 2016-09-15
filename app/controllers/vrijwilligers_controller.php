<?php

class VrijwilligersController extends AppController
{
	public $name = 'Vrijwilligers';

	public $components = array(
			'Filter' => array('persoon_model' => 'Vrijwilliger'),
	);

	public function index()
	{
		$persoon_model = 'Vrijwilliger';

		$this->paginate = array(
			'contain' => array(
				'Geslacht',

			),
		);

		$this->setMedewerkers();

		unset($this->Filter->filterData[$persoon_model.'.selectie']);
		unset($this->Filter->filterData['Groepsactiviteit.selectie']);
		
		$this->Filter->filterData[]='Vrijwilliger.disabled != 1';
		
		$personen = $this->paginate($persoon_model, $this->Filter->filterData);

		if ($persoon_model == 'Klant') {
			$personen = $this->{$persoon_model}->LasteIntake->completeKlantenIntakesWithLocationNames($personen);
		}
		
		$rowOnclickUrl = array(
				'controller' => 'vrijwilligers',
				'action' => 'edit',
		);

		$this->set(compact('personen', 'rowOnclickUrl', 'persoon_model'));

		if ($this->RequestHandler->isAjax()) {
			$this->render('/elements/personen_lijst', 'ajax');
		}
		
	}

	public function view($id = null)
	{
		
		if (!$id) {
			$this->Session->setFlash(__('Invalid vrijwilliger', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('vrijwilliger', $this->Vrijwilliger->read(null, $id));
	}

	public function add()
	{
		
		if (!empty($this->data)) {
			
			$this->Vrijwilliger->create();
			$this->Vrijwilliger->begin();

			if ($this->Vrijwilliger->save($this->data)) {
				
				$this->Session->setFlash(__('The vrijwilliger has been saved', true));
				$referer = array('action' => 'index');
				$this->flash(__('The vrijwilliger has been saved', true));
				
				if (!empty($this->data['Vrijwilliger']['referer'])) {
					
					$referer=$this->data['Vrijwilliger']['referer'];
					if (preg_match('/IzDeelnemers/', $this->data['Vrijwilliger']['referer'])) {
						$referer=array('controller' => 'iz_deelnemers', 'action' => 'aanmelding', 'Vrijwilliger', $this->Vrijwilliger->id);
					}
					if (preg_match('/iz_deelnemers/', $this->data['Vrijwilliger']['referer'])) {
						$referer=array('controller' => 'iz_deelnemers', 'action' => 'aanmelding', 'Vrijwilliger', $this->Vrijwilliger->id);
					}
					
				}

				$this->Vrijwilliger->commit();
				
				if (!empty($this->Vrijwilliger->send_admin_email)) {
					$this->crmUpdate($this->Vrijwilliger->id);
				}

				$this->redirect($referer);
				
			} else {
				
				$this->Vrijwilliger->rollback();
				$this->Session->setFlash(__('The vrijwilliger could not be saved. Please, try again.', true));
				
			}
			
		} else {
			
			$this->data['Vrijwilliger']['referer'] = $this->referer();
			
		}
		
		$this->setmetadata();
		
	}

	private function crmUpdate($id)
	{
		
		$nationaliteiten = $this->Vrijwilliger->Nationaliteit->findList();
		$geslachten = $this->Vrijwilliger->Geslacht->findList();
		$landen = $this->Vrijwilliger->Geboorteland->findList();
		
		$mailto = Configure::read('administratiebedrijf');

		$content=array();
		
		$url = array('controller' => 'vrijwilligers', 'action' => 'view', $id);
		
		$content['url'] = Router::url($url, true);
		$content['changes'] = $this->Vrijwilliger->changes;

		if (isset($content['changes']['geslacht_id'])) {
			
			$content['changes']['geslacht'] = $geslachten[$content['changes']['geslacht_id']];
			unset($content['changes']['geslacht_id']);
			
		}

		if (isset($content['changes']['land_id'])) {
			
			$content['changes']['land'] = $landen[$content['changes']['land_id']];
			unset($content['changes']['land_id']);
			
		}

		if (isset($content['changes']['nationaliteit_id'])) {
			
			$content['changes']['nationaliteit'] = $nationaliteiten[$content['changes']['nationaliteit_id']];
			unset($content['changes']['nationaliteit_id']);
			
		}

		$this->_genericSendEmail(array(
			'to'=>array($mailto),
			'content' => $content,
			'template' => 'crm',
			'subject' => "Er heeft een update in het ECD plaatsgevonden",
		));
	}

	public function edit($id = null)
	{
		
		$persoon_model = 'Vrijwilliger';
		
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid vrijwilliger', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			
			$this->Vrijwilliger->create();
			
			if ($this->Vrijwilliger->save($this->data)) {
				
				if (!empty($this->Vrijwilliger->send_admin_email)) {
					$this->crmUpdate($id);
				}

				$this->Session->setFlash(__('The vrijwilliger has been saved', true));
				$this->redirect($this->data['Vrijwilliger']['referer']);
				
			} else {
				
				$this->Session->setFlash(__('The vrijwilliger could not be saved. Please, try again.', true));
				
			}
		}
		
		if (empty($this->data)) {
			
			$this->data = $this->Vrijwilliger->read(null, $id);
			$this->data['Vrijwilliger']['referer'] = $this->referer();
			
		}
		
		$this->setmetadata($id);
		
	}
	private function setmetadata($id = null)
	{
		$persoon_model = 'Vrijwilliger';
		
		$geslachten = $this->Vrijwilliger->Geslacht->find('list');
		$landen = $this->Vrijwilliger->Geboorteland->find('list');																																			
		$nationaliteiten = $this->Vrijwilliger->Nationaliteit->find('list');
		
		$this->setMedewerkers();
		
		$werkgebieden = Configure::read('Werkgebieden');
		$postcodegebieden = Configure::read('Postcodegebieden');
		
		$this->set(compact('id', 'geslachten', 'landen', 'nationaliteiten', 'medewerkers', 'werkgebieden', 'postcodegebieden', 'persoon_model'));
	}

	public function disable($id = null)
	{
		if (!$id) {
			
			$this->Session->setFlash(__('Invalid id for vrijwilliger', true));
			$this->redirect(array('action'=>'index'));
			
		}
		
		$data = array(
			'Vrijwilliger' => array(
				 'id' => $id,
				 'disabled' => true,
			 ),
		);
		
		$this->Vrijwilliger->create();
		
		unset($this->Vrijwilliger->validate['achternaam']);
		unset($this->Vrijwilliger->validate['medewerker_id']);
		
		if ($this->Vrijwilliger->save($data)) {
			
			$this->Session->setFlash(__('Vrijwilliger deleted', true));
			$this->redirect(array('action'=>'index'));
			
		}
		
		$this->Session->setFlash(__('Vrijwilliger was not deleted', true));
		$this->redirect(array('action' => 'edit', $id));
		
	}

	public function get_stadsdeel()
	{
		$postcode = $this->getParam('postcode');

		$result = array(
			'stadsdeel' => false,
			'message' => 'Error',
		);

		if ($postcode) {
			
			$this->loadModel('Stadsdeel');
			$this->loadModel('Postcodegebied');
			
			$stadsdeel = $this->Stadsdeel->getStadsdeelByPostcode($postcode);
			$postcodegebied = $this->Postcodegebied->getPostcodegebiedByPostcode($postcode);

			if ($stadsdeel && $postcodegebied) {
				
				$result['stadsdeel'] = $stadsdeel;
				$result['postcodegebied'] = $postcodegebied;
				$result['message'] = 'Success';
				
			}
		}

		$result['data'] = $this->data;
		$this->set('jsonVar', $result);
		$this->render('/elements/json');
		
	}
}
