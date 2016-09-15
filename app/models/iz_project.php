<?php

class IzProject extends AppModel
{
	public $name = 'IzProject';
	public $displayField = 'naam';

	public $actsAs = array(
			'Containable',
	);

	public $hasAndBelongsToMany = array(
		'IzDeelnemer' => array(
			'className' => 'IzDeelnemer',
			'joinTable' => 'iz_deelnemers_iz_projecten',
			'foreignKey' => 'iz_project_id',
			'associationForeignKey' => 'iz_deelnemer_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => '',
		),
	);

	public function  beforeSave(&$model)
	{
		Cache::delete($this->getcachekey(false));
		Cache::delete($this->getcachekey(true));
		return true;
	}

	public function getcachekey($all = true)
	{
		$cachekey = "IzProjectenList";
		
		if ($all) {
			return $cachekey;
		}
		
		$cachekey .= date('Y-m-d');
		
		return $cachekey;
	}

	public function getProjects()
	{
		
		$projects = $this->find('all', array(
			'contain' => array(),
		));
		
		return $projects;
	}
	public function projectLists($all = false)
	{
		
		$cachekey = $this->getcachekey($all);
		$projectlists = Cache::read($cachekey);
		
		if (!empty($projectlists)) {
			return $projectlists;
		}

		if ($all) {
			$conditions = array();
		} else {
			
			$conditions = array(
				'OR' => array(
					array(
						'startdatum <= now()',
						'einddatum >= now()',
					),
					array(
						'startdatum <= now()',
						'einddatum' => null,
					),
				),
			 );
			
		}
		
		$projectlists = $this->find('list', array(
			'conditions' => $conditions,
			'order' => 'naam asc',
		));

		Cache::write($cachekey, $projectlists);
		
		return $projectlists;
	}
}
