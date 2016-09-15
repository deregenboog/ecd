<?php

class IzKoppeling extends AppModel
{
	public $name = 'IzKoppeling';
	public $displayField = 'iz_deelnemer_id';

	public $actsAs = array('Containable');

	public $validate = array(
			'medewerker_id' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een medewerker in',
							'allowEmpty' => false,
							'required' => false,
					),
			),
			'iz_vraagaanbod_id' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een reden in',
							'allowEmpty' => false,
							'required' => false,
					),
			),
			'iz_eindekoppeling_id' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een reden in',
							'allowEmpty' => false,
							'required' => false,
					),
			),
			'startdatum' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een startdatum in',
							'allowEmpty' => false,
							'required' => false,
					),
			),
			'iz_koppeling_id' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Selecteer een koppeling',
							'allowEmpty' => false,
							'required' => false,
					),
			),
			'koppleling_startdatum' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een startdatum in',
							'allowEmpty' => false,
							'required' => false,
					),
			),
			'project_id' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een project in',
							'allowEmpty' => false,
							'required' => false,
					),
			),
	);

	public $belongsTo = array(
		'IzDeelnemer' => array(
			'className' => 'IzDeelnemer',
			'foreignKey' => 'iz_deelnemer_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'Medewerker' => array(
			'className' => 'Medewerker',
			'foreignKey' => 'medewerker_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'IzEindekoppeling' => array(
			'className' => 'IzEindekoppeling',
			'foreignKey' => 'iz_eindekoppeling_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'IzVraagaanbod' => array(
			'className' => 'IzVraagaanbod',
			'foreignKey' => 'iz_vraagaanbod_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),

	);

	public function getCandidatesForProjects($persoon_model, $project_ids)
	{

		if ($persoon_model == 'Klant') {
			$model = 'Vrijwilliger';
		} else {
			$model = 'Klant';
		}

		$contain = array(
			'IzDeelnemer',
		);

		$today=date('Y-m-d');

		$conditions = array(
			'IzDeelnemer.model' => $model,
			'IzKoppeling.iz_koppeling_id' => null,
			'IzKoppeling.project_id' => $project_ids,
		 	array(
			   'OR' => array(
					'IzKoppeling.einddatum' => null,
					'IzKoppeling.einddatum >=' =>  $today,
		   		),
		 	),
		 	array(
				'OR' => array(
					'IzKoppeling.startdatum' => null,
					'IzKoppeling.startdatum <=' => $today,
		   		),
			),
		);

		$all = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => $contain,
		));

		foreach ($all as $key => $a) {
			$all[$key][$model] = $this->IzDeelnemer->{$model}->getById($a['IzDeelnemer']['foreign_key']);
		}

		$projects = array();
		
		foreach ($project_ids as $p_id) {
			$projects[$p_id] = array();
		}
		
		foreach ($all as $a) {
			$projects[$a['IzKoppeling']['project_id']][$a['IzKoppeling']['id']]=$a[$model]['name'];
		}

		return $projects;
	}
}
