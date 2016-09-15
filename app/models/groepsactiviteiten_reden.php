<?php

class GroepsactiviteitenReden extends AppModel
{
	public $name = 'GroepsactiviteitenReden';
	public $displayField = 'naam';

	public $actAs = array(
		'Containable',
	);

	public $validate = array(
			'naam' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een reden in',
							'allowEmpty' => false,
							'required' => true,
					),
			),
	);

	public $list_cache_key = 'GroepsactiviteitenReden.list_cache_key';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $hasMany = array(
		'GroepsactiviteitenGroepenVrijwilliger' => array(
			'className' => 'GroepsactiviteitenGroepenVrijwilliger',
			'foreignKey' => 'groepsactiviteiten_reden_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => '',
		),
	);

	public function save($data = null, $validate = true, $fieldList = array())
	{
		Cache::delete($this->list_cache_key);
		return parent::save($data, $validate, $fieldList);
	}

	public function get_groepsactiviteiten_reden()
	{
		$groepsactiviteiten_reden = Cache::read($this->list_cache_key);

		if (! empty($groepsactiviteiten_reden)) {
			return $groepsactiviteiten_reden;
		}

		$this->recursive = -1;
		$groepsactiviteiten_reden = $this->find('all', array(
				'contain' => array(), // seems not te be working...
		));
		Cache::write($this->list_cache_key, $groepsactiviteiten_reden);

		return $groepsactiviteiten_reden;
	}

	public function get_groepsactiviteiten_reden_list()
	{
		$groepsactiviteiten_reden = $this->get_groepsactiviteiten_reden();

		$groepsactiviteiten_reden_list = array();
		foreach ($groepsactiviteiten_reden as $r) {
			$groepsactiviteiten_reden_list[$r['GroepsactiviteitenReden']['id']] = $r['GroepsactiviteitenReden']['naam'];
		}

		return $groepsactiviteiten_reden_list;
	}
}
