<?php

class GroepsactiviteitenGroepenController extends AppController
{
	public $name = 'GroepsactiviteitenGroepen';

	public function index($showall = false)
	{
		
		$this->GroepsactiviteitenGroep->recursive = 0;
		
		$conditions = array(
			'OR' => array(
				'einddatum ' => null,
				'einddatum > ' => date('Y-m-d'),
			),
		);
		
		if (!empty($showall)) {
			$conditions = array(
			   'einddatum <= ' => date('Y-m-d'),
			);
		}
		
		$this->paginate = array(
				'conditions' => $conditions,
		);

		$this->set('groepsactiviteitenGroepen', $this->paginate());
	}

	public function add()
	{
		if (!empty($this->data)) {
			
			$this->GroepsactiviteitenGroep->create();
			
			if ($this->GroepsactiviteitenGroep->save($this->data)) {
				
				$this->Session->setFlash(__('De groep is opgeslagen', true));
				$this->redirect(array('action' => 'index'));
				
			} else {
				
				$this->Session->setFlash(__('Groep kan niet worden opgeslagen', true));
				
			}
		}
		
		$werkgebieden = Configure::read('Werkgebieden');
		
		$this->set('werkgebieden', $werkgebieden);
	}

	public function edit($id = null)
	{
		
		if (!$id && empty($this->data)) {
			
			$this->Session->setFlash(__('Niet geldige groep', true));
			$this->redirect(array('action' => 'index'));
			
		}
		
		if (!empty($this->data)) {
			
			$this->data['GroepsactiviteitenGroep']['id'] = $id;
			
			if ($this->GroepsactiviteitenGroep->save($this->data)) {
				
				$this->Session->setFlash(__('De groep is opgeslagen', true));
				$this->redirect(array('action' => 'index'));
				
			} else {
				
				$this->Session->setFlash(__('Groep kan niet worden opgeslagen', true));
				
			}
		}
		if (empty($this->data)) {
			
			$this->GroepsactiviteitenGroep->recursive = 0;
			$this->data = $this->GroepsactiviteitenGroep->read(null, $id);
			
		}
	}

	public function export($id, $persoon_model = 'Klant')
	{
		
		$this->autoLayout = false;
		$this->layout = false;

		$model = $this->name.$persoon_model;

		$groepsactiviteiten_list = $this->GroepsactiviteitenGroep->get_groepsactiviteiten_list();
		$groep = $groepsactiviteiten_list[$id];

		$this->loadModel($model);

		$params = array(
			'contain' => array($persoon_model => array('GroepsactiviteitenIntake')),
			'conditions' => array(
				'groepsactiviteiten_groep_id' => $id,
				'OR' => array(
					'einddatum > now()',
					'einddatum' => null,
				),
			),
		);

		$members = $this->{$model}->find('all', $params);

		$this->set('groep', $groep);
		$this->set('members', $members);
		$this->set('model', $model);
		$this->set('persoon_model', $persoon_model);

		$file = "{$groep}_{$persoon_model}_lijst.xls";
		
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$file\";");
		header("Content-Transfer-Encoding: binary");
		
		$this->render('groep_excel');
	}
}
