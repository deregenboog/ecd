<?php
class RegistratiesController extends AppController {

	var $name = 'Registraties';
	var $components = array('Filter', 'RequestHandler', 'Session');
	var $uses = array('Registratie', 'Klant');
	
	function isAuthorized() {
		
		if (!parent::isAuthorized())   {
			return false;
		}
		
		$user_groups = $this->AuthExt->user('Group');
		$volonteers = Configure::read('ACL.volonteers');
		
		if (empty($user_groups)) {
			return false;
		}
		
		$action_locaties_filter = array(
			'index' => 0,
			'ajaxUpdateShowerList' => 1,
			'ajaxUpdateMWList' => 1,
			'registratieCheckOut' => 1,
			'registratieCheckOutAll' => 0,
			'ajaxAddRegistratie' => 1,
			'delete' => 1,
			'sortRegistraties' => 0,
			'setRegistraties' => 0,
		);
		
		$username  = $this->AuthExt->user('username');
		
		if(isset($volonteers[$username])){
			if(isset($action_locaties_filter[$this->action])){
				if(!empty($this->params['pass'])){
					if(!isset($this->params['pass'][$action_locaties_filter[$this->action]]) ||
					($this->params['pass'][$action_locaties_filter[$this->action]] !== $volonteers[$username])){
						return false;
					}
				}
			}
		}  
		
		return true;
	}

	/*
	|  1 | Blaka Watra					  |
	|  2 | Princehof					  |
	|  5 | AMOC							  |
	|  9 | De Eik						  |
	| 10 | De Kloof						  |
	| 11 | Makom						  |
	| 12 | Nachtopvang De Regenboog Groep |
	| 13 | Ondro Bong					  |
	| 14 | Oud West						  |
	| 15 | De Spreekbuis				  |
	| 16 | Tabe Rienks Huis				  |
	| 17 | Vrouwen Nacht Opvang			  |
	| 18 | Westerpark					  |
	| 19 | Droogbak						  |
	| 20 | Valentijn					  |
	| 21 | Blaka Watra Gebruikersruimte   |
	| 22 | Amoc Gebruikersruimte		  |
	| 23 | Noorderpark					  |
	*/
	
	function index($locatie_id = null){
		
		if ($locatie_id && $locatie = $this->Registratie->Locatie->getById($locatie_id) ) {
			ts('index');

			$this->disableCache();
			$conditions = $this->Filter->filterData;
			
			$conditions[] = array('LasteIntake.toegang_inloophuis' => 1);
			if(! empty($locatie['gebruikersruimte'])){ //Blaka Watra Gebruikersruimte , Amoc Gebruikersruimte , Princehof
				$conditions[] = array('LasteIntake.locatie1_id' => $locatie_id);
			} else if ($locatie['id'] == 17 ) { // Vrouwen Nacht Opvang
				
				$conditions[]['Geslacht.afkorting'] = 'V';
				$conditions[]['LasteIntake.toegang_vrouwen_nacht_opvang'] = 1;
				
			} else if ($locatie['id'] == 5 ) { // Amoc
			} else if ($locatie['id'] == 12 ) { //Nachtopvang De Regenboog Groep
			} else { // Rest
				$conditions[] = array('OR' => 
					array(
						'LasteIntake.verblijfstatus_id NOT ' => 7,
						array(
							'LasteIntake.verblijfstatus_id' => 7,
							"DATE_ADD(Klant.first_intake_date, INTERVAL 3 MONTH) < now()",
						)
					)
				);
			}
			$conditions[] = array(
				'OR' => array(
					'Klant.overleden NOT' => 1,
					'Klant.overleden' => null,
				)
			);
			$this->log($conditions,'registratie');

			$this->paginate['Klant'] = array( 
					
				'contain' => array (
					'Geslacht' => array (
						'fields' => array (
							'afkorting', 
							'volledig' 
						) 
					), 
					'LasteIntake' => array (
						'fields' => array (
							'locatie1_id', 
							'locatie2_id', 
							'locatie3_id', 
							'datum_intake' 
						), 
					) 
				),
				'conditions' => $conditions,
				'order' => array (
					'Klant.achternaam' => 'asc', 
					'Klant.voornaam' => 'asc' 
				)
			);
			
			$this->Klant->recursive = -1;
			ts('paginate');
			
			$klanten = $this->paginate('Klant');
			ts('paginated');
			
			$klanten = $this->Klant->LasteIntake->completeKlantenIntakesWithLocationNames($klanten);

			
			$klanten = $this->Klant->completeVirtualFields($klanten);
			ts('completed');

			$this->Klant->Schorsing->get_schorsing_messages($klanten, $locatie_id);
			ts('got messages');
			
			$this->set('klanten', $klanten);
			$this->set('add_to_list',1);
			$this->set('locatie_id',$locatie_id);

			$loc_name = $locatie['naam'];
			$this->set('locatie_name',$loc_name);
			$this->setRegistraties($locatie_id);
			$this->set('locaties',$this->Registratie->Locatie->find('list'));
			
			if($this->RequestHandler->isAjax()){
				$this->render('/elements/registratie_klantenlijst', 'ajax');
			}
			
		} else {
			
			$this->set('locaties',$this->Registratie->Locatie->locaties(array('maatschappelijkwerk' => 0)));
			$this->render('locaties');
			
		}
	}
	
	function ajaxUpdateShowerList($action, $locatie_id = null, $registratie_id = null){
		
		if($this->RequestHandler->isAjax()){
			
			$registraties =& $this->Registratie->updateShowerList($action,$registratie_id,$locatie_id);
			$this->setRegistraties($locatie_id);
			$this->set('locatie_id',$locatie_id);
			$this->Registratie->Locatie->id = $locatie_id;
			$loc_name = $this->Registratie->Locatie->field('Naam');
			$this->set('locatie_name',$loc_name);
			$this->render('/elements/registratielijst','ajax');
			
		}else{
			
			$this->autoRender = false;
			
		}
	}
	
	function ajaxUpdateQueueList($action, $fieldname = 'mw', $locatie_id = null, $registratie_id = null){
		
		if($this->RequestHandler->isAjax()){
			
			$registraties =& $this->Registratie->updateQueueList($action,$fieldname,$registratie_id,$locatie_id);
			$this->setRegistraties($locatie_id);
			$this->set('locatie_id',$locatie_id);
			$this->Registratie->Locatie->id = $locatie_id;
			$loc_name = $this->Registratie->Locatie->field('Naam');
			$this->set('locatie_name',$loc_name);
			$this->render('/elements/registratielijst','ajax');
			
		}else{
			
			$this->autoRender = false;
			
		}
	}
	
	function ajaxUpdateRegistratie($registratie_id = null){
		
		if($this->RequestHandler->isAjax()){
			
			$this->set('fieldname', key($this->data['Registratie']));
			$this->data['Registratie']['id'] = $registratie_id;
			$this->Registratie->save($this->data);
			$this->set('registratie',$this->Registratie->find('first', array(
				'conditions' => array(
					'Registratie.id' => $registratie_id,
				),
				'contain' => array('Klant' => array('Intake'))
			)));
			$this->render('/elements/registratie_checkboxes','ajax');
			
		}else{
			
			$this->autoRender = false;
			
		}
	}

	function ajaxShowHistory($locatie_id) {
		
		if(!isset($this->data['History']['history_limit']) ||
			empty($this->data['History']['history_limit'])
		){
			$day_limit = 0;
		}else{
			$day_limit = $this->data['History']['history_limit'];
		}

		$this->setRegistraties($locatie_id, $day_limit);
		$this->Registratie->Locatie->id = $locatie_id;
		$locatie_name = $this->Registratie->Locatie->field('Naam');
		$this->set(compact('locatie_name', 'locatie_id'));

		$this->render('/elements/registratielijst', 'ajax');
	}
	
	function view($id = null) {
		
		if (!$id) {
			$this->flashError(__('Invalid registratie', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('registratie', $this->Registratie->read(null, $id));
	}
	
	function registratieCheckOut($registratie_id = null, $locatie_id = null){
		
		if($registratie_id){
			if($this->Registratie->registratieCheckOut($registratie_id)){
				
			}
		}
		
		$this->setRegistraties($locatie_id);
		$this->set('locatie_id',$locatie_id);
		$this->Registratie->Locatie->id = $locatie_id;
		$loc_name = $this->Registratie->Locatie->field('Naam');
		$this->set('locatie_name',$loc_name);
		$this->render('/elements/registratielijst', 'ajax');	
		
	}

	function registratieCheckOutAll($locatie_id = null){
		
		if($locatie_id){
			$conditions = array( 'conditions' => array (
						'locatie_id' => $locatie_id,
						'buiten' => NULL),
						'contain' => array('Klant'));
			$registraties = $this->Registratie->find('list', $conditions);
			foreach ($registraties as $registratie_id) {
				$this->Registratie->registratieCheckOut($registratie_id);
			}
		}
		
		$this->setRegistraties($locatie_id);
		$this->set('locatie_id',$locatie_id);
		$this->Registratie->Locatie->id = $locatie_id;
		$loc_name = $this->Registratie->Locatie->field('Naam');
		$this->set('locatie_name',$loc_name);
		$this->render('/elements/registratielijst', 'ajax');	
		
	}
	
	function ajaxAddRegistratie($klant_id = null, $locatie_id = null){
		
		if(1 || $this->RequestHandler->isAjax()){
			if($klant_id && $locatie_id){
				$this->Registratie->checkoutKlantFromAllLocations($klant_id);
				$this->Registratie->addRegistratie($klant_id, $locatie_id);
			}
			$this->setRegistraties($locatie_id);
			$this->set('locatie_id',$locatie_id);
			$this->Registratie->Locatie->id = $locatie_id;
			$loc_name = $this->Registratie->Locatie->field('Naam');
			$this->set('locatie_name',$loc_name);
			$this->render('/elements/registratielijst', 'ajax');
			
		}else{
			
			$this->render('/elements/registratielijst');
		}
		
	}

	function edit($id = null) {
		
		if (!$id && empty($this->data)) {
			$this->flashError(__('Invalid registratie', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			if ($this->Registratie->save($this->data)) {
				$this->flashError(__('The registratie has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->flashError(__('The registratie could not be saved. Please, try again.', true));
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->Registratie->read(null, $id);
		}
		
		$locaties = $this->Registratie->Locatie->find('list');
		$klanten = $this->Registratie->Klant->find('list');
		$this->set(compact('locaties', 'klanten'));
		
	}

	function delete($id = null, $locatie_id = null) {
		
		if(empty($id) || empty($locatie_id)){
			$this->render('/elements/registratielijst');
			return;
		}
		
		if($this->RequestHandler->isAjax()){
			
			if (!$id) {
				$this->flashError(__('Invalid id for registratie', true));
			}
			
			$this->Registratie->recursive = -1;
			$registratie = $this->Registratie->findById($id);
			$this->Registratie->removeKlantFromAllQueueLists($registratie);
			$klant_id = $registratie['Registratie']['klant_id'];
			
			if ($this->Registratie->delete($id)) {
				$this->Registratie->Klant->set_last_registration($klant_id);
			}
			
			$this->setRegistraties($locatie_id);
			$this->set('locatie_id',$locatie_id);
			$this->Registratie->Locatie->id = $locatie_id;
			$loc_name = $this->Registratie->Locatie->field('Naam');
			$this->set('locatie_name',$loc_name);
			$this->render('/elements/registratielijst', 'ajax');
			
		}else{
			
			$this->render('/elements/registratielijst');
			
		}
	}
	
	function setRegistraties($locatie_id, $history_limit = 0){
		
		ts('setRegistraties');
		$gebruikersruimte_registraties = array();
		$active_registraties = array();

		$this->Registratie->getActiveRegistraties(
			$active_registraties,
			$gebruikersruimte_registraties,
			$locatie_id
		);
		ts('setRegistraties 1');
		
		$past_registraties = $this->Registratie->getRecentlyUnregistered(
			$locatie_id,
			$history_limit,
			$active_registraties,
			$gebruikersruimte_registraties
		);
		ts('setRegistraties 2');
		
		$this->Registratie->setMessages($active_registraties);
		ts('setRegistraties 3');
		
		$this->Registratie->setMessages($gebruikersruimte_registraties);
		ts('setRegistraties 4');
		
		$this->Registratie->setMessages($past_registraties);
		ts('setRegistraties 5');
		
		$this->set('active_registraties',$active_registraties);
		$this->set('gebruikersruimte_registraties',$gebruikersruimte_registraties);
		$this->set('past_registraties',$past_registraties);	
		$this->set('total_registered_clients', count($active_registraties));
		ts('setRegistraties end');
		
	}

	function set_last_registrations(){
		
		$this->Registratie->Klant->recursive = -1;
		$klanten = $this->Registratie->Klant->find('all', array(
			'fields' => array('id', 'laatste_registratie_id')
		));

		foreach($klanten as $klant){
			if(empty($klant['Klant']['laatste_registratie_id'])){
				$this->Registratie->Klant->set_last_registration($klant['Klant']['id']);
			}
		}
	}

	function jsonCanRegister($klant_id, $locatie_id, $h = 1){
		
		$this->Klant->set_registration_virtual_fields();
		$this->Klant->contain[] = 'LaatsteRegistratie';
		$klant =& $this->Klant->find('first',array(
			'conditions' => array('Klant.id' => $klant_id),
		));
		$this->Registratie->Locatie->recursive = -1;
		$location = $this->Registratie->Locatie->read(null, $locatie_id);

		$jsonVar = array(
			'confirm' => false,
			'allow' => true,
			'message' => ''
			);

		$sep = '';
		$separator = PHP_EOL. PHP_EOL;

		if (
			!empty($location['Locatie']['gebruikersruimte']) &&
			!empty($klant['LasteIntake']['mag_gebruiken']) &&
			! $klant['Klant']['laatste_TBC_controle']
		) {
			$jsonVar['allow'] = false;
			$jsonVar['message'] = 'Deze klant heeft geen TBC controle gehad en kan niet worden ingecheckt bij een locatie met een gebruikersruimte.';
			goto render;
		}

		$this->loadModel('LocatieTijd');
		if (! $this->LocatieTijd->isOpen($locatie_id, time())) {
			$jsonVar['allow'] = false;
			$jsonVar['message'] = 'Deze locatie is nog niet open, klant kan nog niet inchecken!';
			goto render;
		}

		if (!empty($klant['LaatsteRegistratie']['id'])) {
			if (empty($klant['LaatsteRegistratie']['buiten'])) {

				if ($klant['LaatsteRegistratie']['locatie_id'] == $locatie_id) {
					$jsonVar['allow'] = false;
					$jsonVar['message'] .= $sep.'Deze klant is op dit moment al ingechecked op deze locatie.';
				} else {
					$jsonVar['confirm'] = true;
					$jsonVar['message'] .= $sep.'Deze klant is op dit moment al ingechecked op andere locatie. Toch inchecken?';
					$sep = $separator;

				}

				$sep = $separator;

			} else {
				$last_check_out = new
					DateTime($klant['LaatsteRegistratie']['buiten']);

				$now = new DateTime();
				$d = $last_check_out->diff($now);

				if($d->h < $h && $d->d == 0 && $d->m == 0 && $d->y == 0){
					$jsonVar['message'] .= $sep.
			__('This client has been checked out less than an hour ago. '.
				'Are you sure you want to register him/her again?', true);

					$jsonVar['confirm'] = true;
					$sep = $separator;
				}
			}
		}

		if ($jsonVar['allow']) {

		if($klant['Klant']['new_intake_needed'] < 0){
			$jsonVar['message'] .= $sep.'Let op: deze persoon heeft momenteel een verlopen intake. Toch inchecken?';
			$sep = $separator;
			$jsonVar['confirm'] = true;
		}

		$schorsing = $this->Registratie->Klant->Schorsing->countActiveSchorsingenMsg($klant_id);
		
		if( $schorsing == 'Hier geschorst'){
			$jsonVar['message'] .= $sep.'Let op: deze persoon is momenteel op deze locatie geschorst. Toch inchecken?';
			$sep = $separator;
			$jsonVar['confirm'] = true;
		}

		if( $klant['Klant']['new_TBC_check_needed'] == 'Ja'){
			$jsonVar['message'] .= $sep.'Let op: deze persoon heeft een nieuwe TBC-check nodig. Toch inchecken?';
			$jsonVar['confirm'] = true;
			$sep = $separator;
		}

		}

		render:
			$this->set(compact('jsonVar'));
			$this->render('/elements/json', 'ajax');

	}

	function jsonSimpleCheckboxToggle($fieldname, $registratie_id){
		$this->Registratie->recursive = -1;
		$this->Registratie->Id = $registratie_id;

		$record = $this->Registratie->read($fieldname, $registratie_id);

		if(empty($record)){
			$jsonVar = 'error';
			$this->set(compact('jsonVar'));
			$this->render('/elements/json', 'ajax');
			return;
		}

		$prev_val = (int)($record['Registratie'][$fieldname]);

		if($fieldname == 'douche' || $fieldname == 'mw'){
			$new_val = -1-$prev_val;//we asume that DB data is correct
		}else{
			$new_val = !($prev_val);
		}

		$this->Registratie->set($fieldname, $new_val);

		if($this->Registratie->save()){
			$jsonVar = array('new_val' => $new_val, 'prev_val' => $prev_val);
		}else{
			$jsonVar = array('new_val' => $prev_val, 'prev_val' => $prev_val);
		}

		$this->set(compact('jsonVar'));
		$this->render('/elements/json', 'ajax');
	}
}
?>
