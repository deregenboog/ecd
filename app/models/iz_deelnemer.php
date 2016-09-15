<?php

class IzDeelnemer extends AppModel
{
	public $name = 'IzDeelnemer';

	public $actsAs = array('Containable', 'OdsEtiketten' );

	public $belongsTo = array(
			'Klant' => array(
					'className' => 'Klant',
					'model' => 'Klant',
					'foreignKey' => 'foreign_key',
					'conditions' => array('model' => 'Klant'),
					'fields' => '',
					'order' => '',
			),
			'Vrijwilliger' => array(
					'className' => 'Vrijwilliger',
					'model' => 'Vrijwilliger',
					'foreignKey' => 'foreign_key',
					'conditions' => array('model' => 'Vrijwilliger'),
					'fields' => '',
					'order' => '',
			),
			'IzAfsluiting' => array(
					'className' => 'IzAfsluiting',
					'foreignKey' => 'iz_afsluiting_id',
					'conditions' => '',
					'fields' => '',
					'order' => '',
			),
	);

	public $hasMany = array(
			'IzDeelnemersIzIntervisiegroep' => array(
					'className' => 'IzDeelnemersIzIntervisiegroep',
					'foreignKey' => 'iz_deelnemer_id',
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
			'IzDeelnemersIzProject' => array(
					'className' => 'IzDeelnemersIzProject',
					'foreignKey' => 'iz_deelnemer_id',
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
			'IzVerslag' => array(
					'className' => 'IzVerslag',
					'foreignKey' => 'iz_deelnemer_id',
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
			'IzKoppeling' => array(
					'className' => 'IzKoppeling',
					'foreignKey' => 'iz_deelnemer_id',
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
			'IzDeelnemerDocument' => array(
					'className' => 'Attachment',
					'foreignKey' => 'foreign_key',
					'conditions' => array(
							'IzDeelnemerDocument.model' => 'IzDeelnemer',
							'is_active' => 1,
					),
					'dependent' => true,
					'order' => 'created desc',
			),
	);

	public $hasOne = array(
		'IzIntake' => array(
			'className' => 'IzIntake',
			'foreignKey' => 'iz_deelnemer_id',
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

	public $validate = array(
			'datum_aanmelding' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een datum in',
							'allowEmpty' => false,
							'required' => false,
					),
			),
			'email_aanmelder' => array(
					'email' => array(
							'rule' => array(
									'email',
							),
							'message' => 'Een geldig E-Mail adres invoeren',
							'allowEmpty' => true,
							'required' => false,
							//'last' => false, // Stop validation after this rule
					),
			),
			'iz_afsluiting_id' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een reden in',
							'allowEmpty' => false,
							'required' => false,
					),
			),
	);

	public function beforeSave($options = array())
	{
		$data = array($this->alias => $this->data[$this->alias]);

		if ($this->id || !empty($this->data['IzDeelnemer']['id'])) {
			
			$id=$this->id;
			
			if (!empty($this->data['IzDeelnemer']['id'])) {
				$id=$this->data['IzDeelnemer']['id'];
			}

			if (!empty($this->data['IzDeelnemer']['model']) || !empty($this->data['IzDeelnemer']['foreign_key'])) {
				
				$iz=$this->getById($id);
				
				if ($iz['model'] != $this->data['IzDeelnemer']['model'] || $iz['foreign_key'] != $this->data['IzDeelnemer']['foreign_key']) {
					
					$this->log("{$id}: {$iz['model']} => {$this->data['IzDeelnemer']['model']},  {$iz['foreign_key']} => {$this->data['IzDeelnemer']['foreign_key']}", 'izdeelnemerfkcheck');

				}
				unset($this->data['IzDeelnemer']['model']);
				unset($this->data['IzDeelnemer']['foreign_key']);
			}

		} 

		return parent::beforeSave($options);
	}

	public function hasActiveKoppelingen($id)
	{
		$iz_koppelingen = $this->IzKoppeling->find('all', array(
			'conditions' => array(
				'OR' => array(
					 array(
						'iz_deelnemer_id' => $id,
						'koppeling_einddatum' => null,
						'iz_koppeling_id NOT' => null,
					 ),
					 array(
						 'iz_deelnemer_id' => $id,
						 'einddatum' => null,
						 'iz_koppeling_id' => null,
					 ),
				 ),
			),
			'contain' => array(),
			'fields' => array('id'),
		));

		if (empty($iz_koppelingen)) {
			return false;
		}
		
		return true;
	}

	private function e($s)
	{
		
		if (is_array($s)) {
			
			foreach ($s as $k => $v) {
				$s[$k]=mysql_escape_string($v);
			}
			
		} else {
			$s=mysql_escape_string($s);
		}
		
		return $s;
	}

	public function getIntervisiePersonen($params)
	{
		$geslachten = $this->Klant->Geslacht->find('list');

		$personen = array();
		$query = "";

		$projectlist = $this->IzDeelnemersIzProject->IzProject->projectLists(true);
		$medewerkers = $this->IzIntake->Medewerker->getMedewerkers(null, null, true);

		$query = "select p.id, 'Vrijwilliger' as model, voornaam, geslacht_id, tussenvoegsel, achternaam, geboortedatum, email, adres, postcode, werkgebied, plaats, mobiel, telefoon, CONCAT_WS(' ', `voornaam`, `tussenvoegsel`, `achternaam`) as name, p.medewerker_id, iz.id as iz_deelnemer_id, group_concat(ip.iz_project_id) as project_ids from vrijwilligers p join iz_deelnemers iz on iz.foreign_key = p.id and iz.model = 'Vrijwilliger' left join iz_deelnemers_iz_projecten ip on ip.iz_deelnemer_id = iz.id	";
		if (! empty($params['IzDeelnemer']['intervisiegroep_id'])) {
			
			$ids = implode(',', $params['IzDeelnemer']['intervisiegroep_id']);
			$ids = mysql_escape_string($ids);
			$query .= " join ( select qiz.id from iz_deelnemers qiz join iz_deelnemers_iz_intervisiegroepen qizg on qizg.iz_deelnemer_id = qiz.id and iz_intervisiegroep_id in ( {$ids} )) as subq on subq.id = iz.id  ";
		
		}

		$where = " (  isnull(datumafsluiting) or datumafsluiting > NOW()) " ;

		if (! empty($params['IzDeelnemer']['export']) && $params['IzDeelnemer']['export'] == 'email') {
			if (! empty($where)) {
				$where .= ' and ';
			}
			$where .= " not isnull(p.email) and email != '' ";
		}

		if (! empty($where)) {
			$query .= " WHERE {$where} ";
		}
		
		$query .= " GROUP BY p.id ";

		$tmp = $this->query($query);

		$model='Vrijwilliger';
		foreach ($tmp as $t) {
			
			$project_ids = explode(',', $t[0]['project_ids']);
			
			$project_names = array();

			foreach ($project_ids as $project_id) {
				
				if (isset($projectlist[$project_id])) {
					$project_names[] = $projectlist[$project_id];
				} else {
					$project_names[] = $project_id;
				}
				
			}

			$s='V';
			$project_names = array_unique($project_names);
			$iz_deelnemer[$model]['klant_nummer']=$s.$t['p']['id'];
			$iz_deelnemer[$model]['model']=$model;
			$iz_deelnemer[$model]['projecten'] = implode(', ', $project_names);
			$iz_deelnemer[$model]['id']=$t['p']['id'];
			$iz_deelnemer[$model]['name']=$t[0]['name'];
			$iz_deelnemer[$model]['voornaam']=$t['p']['voornaam'];
			$iz_deelnemer[$model]['tussenvoegsel']=$t['p']['tussenvoegsel'];
			$iz_deelnemer[$model]['achternaam']=$t['p']['achternaam'];
			$iz_deelnemer[$model]['geboortedatum']=$t['p']['geboortedatum'];
			$iz_deelnemer[$model]['email']=$t['p']['email'];
			$iz_deelnemer[$model]['adres']=$t['p']['adres'];
			$iz_deelnemer[$model]['geslacht_id']=$t['p']['geslacht_id'];
			$iz_deelnemer[$model]['geslacht']=$geslachten[$t['p']['geslacht_id']];
			$iz_deelnemer[$model]['postcode']=$t['p']['postcode'];
			$iz_deelnemer[$model]['werkgebied']=$t['p']['werkgebied'];
			$iz_deelnemer[$model]['plaats']=$t['p']['plaats'];
			$iz_deelnemer[$model]['mobiel']=$t['p']['mobiel'];
			$iz_deelnemer[$model]['telefoon']=$t['p']['telefoon'];
			$iz_deelnemer[$model]['medewerker_id']=$t['p']['medewerker_id'];
			$iz_deelnemer[$model]['coordinator'] = "";

			if (!empty($iz_deelnemer[$model]['medewerker_id'])) {
				$iz_deelnemer[$model]['coordinator'] = $medewerkers[$iz_deelnemer[$model]['medewerker_id']];
			}
			
			$personen[] = $iz_deelnemer[$model];
		}
		
		return $personen;
	}

	private function getQuery($model, $params)
	{
		$model=$this->e($model);
		
		$table = strtolower(Inflector::pluralize($model));
		
		$query = "select p.id, '{$model}' as model, geslacht_id, voornaam, tussenvoegsel, achternaam, geboortedatum, email, adres, postcode, werkgebied, postcodegebied, plaats, mobiel, telefoon, CONCAT_WS(' ', `voornaam`, `tussenvoegsel`, `achternaam`) as name, p.medewerker_id, iz.id as iz_deelnemer_id, group_concat(ip.iz_project_id) project_ids, it.intake_datum, datum_aanmelding, iz.datumafsluiting, iz_afsluiting_id, group_concat(iz_koppeling_id) as iz_koppeling_id, group_concat(k.id) as kid, group_concat(concat(k.id,koppeling_einddatum)) as koppeling_einddatum, group_concat(concat(k.id,einddatum)) as einddatum, it.stagiair, it.gezin_met_kinderen, geen_post, geen_email from {$table} p join iz_deelnemers iz on iz.foreign_key = p.id and iz.model = '{$model}' left join iz_koppelingen k on k.iz_deelnemer_id = iz.id left join iz_intakes it on it.iz_deelnemer_id = iz.id ";
		$query .= " left join iz_deelnemers_iz_projecten ip on ip.iz_deelnemer_id = iz.id " ;

		$where = " 1 = 1 ";
		
		if (! empty($params['IzDeelnemer']['project_id']) && ! in_array('iedereen', $params['IzDeelnemer']['project_id'])) {
			
			$where .= " and ip.iz_project_id in ( ";
			$where .= "'".implode("','", $this->e($params['IzDeelnemer']['project_id']))."'";
			$where .= " ) ";
			
		}

		if (! empty($params['IzDeelnemer']['werkgebieden'])) {
			
			$t="";

			$t .= " werkgebied in ( ";
			$t .= "'".implode("','", $this->e($params['IzDeelnemer']['werkgebieden']))."'";
			$t .= " ) ";

			if (in_array('geen_werkgebied', $params['IzDeelnemer']['werkgebieden'])) {
				
				if (! empty($t)) {
					$t.=" or ";
				}
				
				$t .= " ( isnull(werkgebied) or werkgebied = '' ) ";
				
			}
			
			$where.=" and ( {$t} ) ";
		}
		if (! empty($params['IzDeelnemer']['postcodegebieden'])) {
			
			$t="";
			$t .= " postcodegebied in ( ";
			$t .= "'".implode("','", $this->e($params['IzDeelnemer']['postcodegebieden']))."'";
			$t .= " ) ";

			if (in_array('geen_postcodegebied', $params['IzDeelnemer']['postcodegebieden'])) {
				if (! empty($t)) {
					$t.=" or ";
				}
				$t .= " ( isnull(postcodegebied) or postcodegebied = '' ) ";
			}
			
			$where.=" and ( {$t} ) ";
			
		}

		if (is_array($params['IzDeelnemer']['communicatie_type']) && ! in_array('iedereen', $params['IzDeelnemer']['communicatie_type'])) {
			
			if (in_array('communicatie_post', $params['IzDeelnemer']['communicatie_type'])) {
				$where .= " and p.geen_post != 1 ";
			} 
			
			if (in_array('communicatie_email', $params['IzDeelnemer']['communicatie_type'])) {
				$where .= " and p.geen_email != 1  and not isnull(p.email) and email != '' ";
			} 
			
			if (in_array('communicatie_telefoon', $params['IzDeelnemer']['communicatie_type'])) {
				$where .= " and ( not isnull(p.telefoon) and p.telefoon != '' or not isnull(p.mobiel) and p.mobiel != '' )	";
			} 
			
		}

		if (! empty($where)) {
			$query .= " WHERE {$where} ";
		}
		
		$query .= " GROUP BY iz.id ";

		return $query;
	}

	public function getPersonen($params)
	{
		
		$personen = array();
		
		$query = "";
		$geslachten = $this->Klant->Geslacht->find('list');
		$count = count($params['IzDeelnemer']['persoon_model']);

		$projectlist = $this->IzDeelnemersIzProject->IzProject->projectLists(true);
		$projects = $this->IzDeelnemersIzProject->IzProject->getProjects();
		$heeft_koppelingen = Set::Combine($projects, "{n}.IzProject.id", "{n}.IzProject.heeft_koppelingen");
		$medewerkers = $this->IzIntake->Medewerker->getMedewerkers(null, null, true);

		foreach ($params['IzDeelnemer']['persoon_model'] as $model) {
			
			$query = $this->getQuery($model, $params);

			if (true) {
				$this->log($query, 'queries');
			}
			if (empty($query)) {
				return $personen;
			}

			$tmp = $this->query($query);

			foreach ($tmp as $t) {

				$project_ids = explode(',', $t[0]['project_ids']);
				$project_names = array();
				$found=false;
				
				foreach ($project_ids as $project_id) {
					if (!empty($project_id)) {
						$found=true;
					}
				}
				
				$hc=false;
				if (empty($found)) {
					$hc=true;
				}
				
				foreach ($project_ids as $project_id) {
					
					if (!empty($heeft_koppelingen[$project_id])) {
						$hc=true;
					}
					
					if (isset($projectlist[$project_id])) {
						$project_names[] = $projectlist[$project_id];
					} else {
						$project_names[] = $project_id;
					}
					
				}

				$fase = 'aanmelding';
				if (! empty($t['it']['intake_datum'])) {
					$fase = 'onvolledig';
				}
				
				$kids=0;
				if (!empty($t[0]['kid'])) {
					$kids=count(array_unique(split(',', $t[0]['kid'])));
				}
				
				$izkids=0;
				if (!empty($t[0]['iz_koppeling_id'])) {
					$izkids=count(array_unique(split(',', $t[0]['iz_koppeling_id'])));
				}
				$eda=0;
				if (!empty($t[0]['einddatum'])) {
					$eda=count(array_unique(split(',', $t[0]['einddatum'])));
				}
				$ed=0;
				if (!empty($t[0]['koppeling_einddatum'])) {
					$ed=count(array_unique(split(',', $t[0]['koppeling_einddatum'])));
				}
				
				if ($eda > $ed) {
					$kids=$kids-$eda+$ed;
				}
				
				if ($kids >= 1 && $kids > $izkids) {
					$fase='wachtlijst';
					if ($kids == $ed) {
						$fase='wachtlijst';
					}
				}
				
				if ($kids >= 1 && $kids == $izkids && $kids > $ed) {
					$fase='gekoppeld';
				}
				
				if ($kids >= 1 && $kids == $izkids && $kids == $ed) {
					$fase='beeindigd';
				}
				if (!empty($t['iz']['datumafsluiting'])) {
					$fase='afgesloten';
				}
				
				if (($fase == 'onvolledig' || $fase == 'aanmelding') &&  empty($hc)) {
					$fase = 'koppeling_nvt';
				}
				
				if (! in_array('iedereen', $params['IzDeelnemer']['iz_fase'])) {
					if (! in_array($fase, $params['IzDeelnemer']['iz_fase'])) {
						continue;
					}
				}

				$s='O';
				if ($model == 'Vrijwilliger') {
					$s='V';
				}
				
				if ($model == 'Klant') {
					$s='K';
				}
				
				$project_names = array_unique($project_names);
				
				$iz_deelnemer[$model]['klant_nummer']=$s.$t['p']['id'];
				$iz_deelnemer[$model]['model']=$model;
				$iz_deelnemer[$model]['fase']=$fase;
				$iz_deelnemer[$model]['projecten'] = implode(', ', $project_names);
				$iz_deelnemer[$model]['id']=$t['p']['id'];
				$iz_deelnemer[$model]['intake_datum']=$t['it']['intake_datum'];
				$iz_deelnemer[$model]['stagiair']=empty($t['it']['stagiair']) ? '' : 'ja';
				$iz_deelnemer[$model]['gezin_met_kinderen']=empty($t['it']['gezin_met_kinderen']) ? '': 'ja';
				$iz_deelnemer[$model]['datumafsluiting']=$t['iz']['datumafsluiting'];
				$iz_deelnemer[$model]['geslacht']=$t['p']['geslacht_id'];
				
				if (isset($geslachten[$t['p']['geslacht_id']])) {
					$iz_deelnemer[$model]['geslacht']=$geslachten[$t['p']['geslacht_id']];
				}
				
				$iz_deelnemer[$model]['name']=$t[0]['name'];
				$iz_deelnemer[$model]['voornaam']=$t['p']['voornaam'];
				$iz_deelnemer[$model]['tussenvoegsel']=$t['p']['tussenvoegsel'];
				$iz_deelnemer[$model]['achternaam']=$t['p']['achternaam'];
				$iz_deelnemer[$model]['geboortedatum']=$t['p']['geboortedatum'];
				$iz_deelnemer[$model]['email']=$t['p']['email'];
				$iz_deelnemer[$model]['adres']=$t['p']['adres'];
				$iz_deelnemer[$model]['geen_post']= ! empty($t['p']['geen_post']) ? 'Ja': 'Nee';
				$iz_deelnemer[$model]['geen_email']= ! empty($t['p']['geen_email']) ? 'Ja' : 'Nee';
				$iz_deelnemer[$model]['postcode']=$t['p']['postcode'];
				$iz_deelnemer[$model]['werkgebied']=$t['p']['werkgebied'];
				$iz_deelnemer[$model]['postcodegebied']=$t['p']['postcodegebied'];
				$iz_deelnemer[$model]['plaats']=$t['p']['plaats'];
				$iz_deelnemer[$model]['mobiel']=$t['p']['mobiel'];
				$iz_deelnemer[$model]['telefoon']=$t['p']['telefoon'];
				$iz_deelnemer[$model]['medewerker_id']=$t['p']['medewerker_id'];
				$iz_deelnemer[$model]['coordinator'] = "";

				if (!empty($iz_deelnemer[$model]['medewerker_id'])) {
					$iz_deelnemer[$model]['coordinator'] = $medewerkers[$iz_deelnemer[$model]['medewerker_id']];
				}
				
				$personen[] = $iz_deelnemer[$model];
			}
		}

		return $personen;
	}

	public function get_werkgebieden()
	{
		$sql = 'SELECT DISTINCT(werkgebied) FROM klanten';
		$data = $this->Klant->query($sql);

		$result = array(
			'' => 'Niet ingevuld',
		);

		foreach ($data as $klant) {
			if (!empty($klant['klanten']['werkgebied'])) {
				$result[$klant['klanten']['werkgebied']] = $klant['klanten']['werkgebied'];
			}
		}

		return $result;
	}

	public function nieuwe_koppelingen_report_html($startDate, $endDate, $labels, $projects=false)
	{
		$result = array(
			'title' => 'Aantal nieuwe koppelingen in deze periode',
			'data' => array(),
		);
		
		foreach ($labels as $key => $value) {
			$result['data'][ $key ] = 0;
		}

		if (empty($projects)) {
			
			$field="k.werkgebied ";
			$groupby = "k.werkgebied ";
			$m = 'k';
			$f = 'werkgebied';
			
		} else {
			
			$field="izk.project_id ";
			$groupby = "izk.project_id ";
			$m = 'izk';
			$f = 'project_id';
			
		}

		$sql = 'SELECT '.$field.', COUNT(*) AS cnt'
			 .' FROM iz_deelnemers izd'
			 .' JOIN klanten k ON k.id = izd.foreign_key AND izd.model = "Klant"'
			 .' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id'
			 .' WHERE izk.iz_koppeling_id IS NOT NULL'
			 .' AND izk.koppeling_startdatum >= "'.$startDate.'"'
			 .' AND izk.koppeling_startdatum <= "'.$endDate.'"'
			 .' GROUP BY '.$groupby
		;

		$data = $this->query($sql);

		foreach ($data as $value) {
			
			if (isset($result['data'][ $value[$m][$f] ])) {
				$result['data'][ $value[$m][$f] ] += $value[0]['cnt'];
			} else {
				$result['data'][ 'Onbekend'] += $value[0]['cnt'];
			}
			
		}
		
		foreach ($result['data'] as $key => $value) {
			$result['data']['Totaal'] += $value;
		}

		return $result;
	}

	public function active_klanten_report_html($startDate, $endDate, $labels, $projects=false)
	{
		$result = array(
			'title' => 'Aantal actieve unieke klanten in deze periode',
			'data' => array(),
		);
		
		foreach ($labels as $key => $value) {
			$result['data'][ $key ] = 0;
		}
		
		if (empty($projects)) {
			
			$field="k.werkgebied ";
			$groupby = "k.werkgebied ";
			$m = 'k';
			$f = 'werkgebied';
			
		} else {
			
			$field="izk.project_id ";
			$groupby = "izk.project_id ";
			$m = 'izk';
			$f = 'project_id';
			
		}

		$sql = 'SELECT '.$field.', COUNT(*) AS cnt'
			 .' FROM iz_deelnemers izd'
			 .' JOIN klanten k ON k.id = izd.foreign_key AND izd.model = "Klant"'
			 .' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id'
			 .' WHERE izk.iz_koppeling_id IS NOT NULL'
			 .' AND izk.koppeling_startdatum < "'.$endDate.'"'
			 .' AND ('
				 .' izk.koppeling_einddatum IS NULL'
				 .' OR izk.koppeling_einddatum > "'.$startDate.'"'
			 .' )'
			 .' GROUP BY '.$groupby
		;

		$data = $this->query($sql);

		foreach ($data as $value) {
			
			if (isset($result['data'][ $value[$m][$f] ])) {
				$result['data'][ $value[$m][$f] ] += $value[0]['cnt'];
			} else {
				$result['data'][ 'Onbekend'] += $value[0]['cnt'];
			}
			
		}

		foreach ($result['data'] as $key => $value) {
			$result['data']['Totaal'] += $value;
		}

		return $result;
	}

	public function active_klanten_op_einddatum_report_html($startDate, $endDate, $labels, $projects=false)
	{
		$result = array(
			'title' => 'Aantal actieve unieke klanten op einddatum',
			'data' => array(),
		);
		
		foreach ($labels as $key => $value) {
			$result['data'][ $key ] = 0;
		}
		
		if (empty($projects)) {
			
			$field="k.werkgebied ";
			$groupby = "k.werkgebied ";
			$m = 'k';
			$f = 'werkgebied';
			
		} else {
			
			$field="izk.project_id ";
			$groupby = "izk.project_id ";
			$m = 'izk';
			$f = 'project_id';
			
		}

		$sql = 'SELECT '.$field.', count(*) as cnt '
			 .' FROM iz_deelnemers izd'
			 .' JOIN klanten k ON k.id = izd.foreign_key AND izd.model = "Klant"'
			 .' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id'
			 .' WHERE izk.iz_koppeling_id IS NOT NULL'
			 .' AND izk.koppeling_startdatum < "'.$endDate.'"'
			 .' AND ('
				 .' izk.koppeling_einddatum IS NULL'
				 .' OR izk.koppeling_einddatum >= "'.$endDate.'"'
			 .' )'
			 .' GROUP BY '.$groupby
		;

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			if (isset($result['data'][ $value[$m][$f] ])) {
				$result['data'][ $value[$m][$f] ] += $value[0]['cnt'];
			} else {
				$result['data'][ 'Onbekend'] += $value[0]['cnt'];
			}
			
		}
		
		foreach ($result['data'] as $key => $value) {
			$result['data']['Totaal'] += $value;
		}

		return $result;
	}

	public function wachtlijst_klanten_report_html($startDate, $endDate, $werkgebieden, $projects=false)
	{
		$result = array(
			'title' => 'Wachtlijst deelnemers op einddatum',
			'data' => array(),
		);
		
		foreach ($werkgebieden as $key => $value) {
			$result['data'][ $key ] = 0;
		}
		
		if (empty($projects)) {
			
			$field="k.werkgebied ";
			$groupby = "k.werkgebied ";
			$m = 'k';
			$f = 'werkgebied';
			
		} else {
			
			$field="izk.project_id ";
			$groupby = "izk.project_id ";
			$m = 'izk';
			$f = 'project_id';
			
		}

		$sql = 'SELECT '.$field.', COUNT(*) AS cnt'
			 .' FROM iz_deelnemers izd'
			 .' JOIN klanten k ON k.id = izd.foreign_key AND izd.model = "Klant"'
			 .' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id'
			 .' WHERE izk.iz_koppeling_id IS NULL'
			 .' AND izk.startdatum < "'.$endDate.'"'
			 .' AND ('
				 .' izk.einddatum IS NULL'
				 .' OR izk.einddatum >= "'.$endDate.'"'
			 .' )'
			 .' GROUP BY '.$groupby
		;

		$data = $this->query($sql);

		foreach ($data as $value) {
			
			if (isset($result['data'][ $value[$m][$f] ])) {
				$result['data'][ $value[$m][$f] ] += $value[0]['cnt'];
			} else {
				$result['data'][ 'Onbekend'] += $value[0]['cnt'];
			}
			
		}
		
		foreach ($result['data'] as $key => $value) {
			$result['data']['Totaal'] += $value;
		}

		return $result;
	}

	public function wachtlijst_vrijwilligers_report_html($startDate, $endDate, $labels, $projects=false)
	{
		$result = array(
			'title' => 'Wachtlijst vrijwilligers op einddatum',
			'data' => array(),
		);
		
		foreach ($labels as $key => $value) {
			$result['data'][ $key ] = 0;
		}
		
		if (empty($projects)) {
			
			$field="v.werkgebied ";
			$groupby = "v.werkgebied ";
			$m = 'v';
			$f = 'werkgebied';
			
		} else {
			
			$field="izk.project_id ";
			$groupby = "izk.project_id ";
			$m = 'izk';
			$f = 'project_id';
			
		}

		$sql = 'SELECT '.$field.', COUNT(*) AS cnt'
			 .' FROM iz_deelnemers izd'
			 .' JOIN vrijwilligers v ON v.id = izd.foreign_key AND izd.model = "Vrijwilliger"'
			 .' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id'
			 .' WHERE izk.iz_koppeling_id IS NULL'
			 .' AND izk.startdatum < "'.$endDate.'"'
			 .' AND ('
				 .' izk.einddatum IS NULL'
				 .' OR izk.einddatum >= "'.$endDate.'"'
			 .' )'
			 .' GROUP BY '.$groupby
		;

		$data = $this->query($sql);

		foreach ($data as $value) {
			
			if (isset($result['data'][ $value[$m][$f] ])) {
				$result['data'][ $value[$m][$f] ] += $value[0]['cnt'];
			} else {
				$result['data'][ 'Onbekend'] += $value[0]['cnt'];
			}
			
		}
		
		foreach ($result['data'] as $key => $value) {
			$result['data']['Totaal'] += $value;
		}

		return $result;
	}

	public function gemiddelde_wachttijd_klant_report_html($startDate, $endDate, $labels, $projects=false)
	{
		$result = array(
			'title' => 'Gemiddelde wachttijd klant (dagen)',
			'data' => array(),
		);
		
		foreach ($labels as $key => $value) {
			$result['data'][ $key ] = 0;
		}
		
		if (empty($projects)) {
			
			$field="k.werkgebied ";
			$groupby = "GROUP BY k.werkgebied ";
			$m = 'k';
			$f = 'werkgebied';
			
		} else {
			
			$field="izk.project_id ";
			$groupby = "GROUP BY izk.project_id ";
			$m = 'izk';
			$f = 'project_id';
			
		}

		$sql = 'SELECT '.$field.', ROUND( AVG( ABS( DATEDIFF(izk.koppeling_startdatum, izk.startdatum) ) ) ) as avg'
			 .' FROM iz_deelnemers izd'
			 .' JOIN klanten k ON k.id = izd.foreign_key AND izd.model = "Klant"'
			 .' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id'
			 .' WHERE izk.iz_koppeling_id IS NOT NULL'
			 .' AND izk.koppeling_startdatum < "'.$endDate.'"'
			 .' AND ('
				 .' izk.koppeling_einddatum IS NULL'
				 .' OR izk.koppeling_einddatum > "'.$startDate.'"'
			 .' )'
			 .' '.$groupby
		;

		$data = $this->query($sql);

		foreach ($data as $value) {
			
			if (isset($result['data'][ $value[$m][$f] ])) {
				$result['data'][ $value[$m][$f] ] += $value[0]['avg'];
			} else {
				$result['data'][ 'Onbekend'] += $value[0]['avg'];
			}
			
		}
		
		$sql = 'SELECT '.$field.', ROUND( AVG( ABS( DATEDIFF(izk.koppeling_startdatum, izk.startdatum) ) ) ) as avg'
			 .' FROM iz_deelnemers izd'
			 .' JOIN klanten k ON k.id = izd.foreign_key AND izd.model = "Klant"'
			 .' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id'
			 .' WHERE izk.iz_koppeling_id IS NOT NULL'
			 .' AND izk.koppeling_startdatum < "'.$endDate.'"'
			 .' AND ('
				 .' izk.koppeling_einddatum IS NULL'
				 .' OR izk.koppeling_einddatum > "'.$startDate.'"'
			 .' )'
			 .' '
		;

		$data = $this->query($sql);
		$result['data']['Totaal']=$data[0][0]['avg'];

		return $result;
	}
	
	public function aanvullend_contact_html($startDate, $endDate)
	{
		$results=array();
		
		$sql = "select izc.naam, count(*) as cnt from iz_deelnemers izd "
			." left join iz_ontstaan_contacten izc on izc.id = contact_ontstaan  "
			." where model = 'Klant' "
			." and datum_aanmelding >=	'".$startDate."' "
			." and datum_aanmelding <= '".$endDate."' "
			." group by izc.id";
		
		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$key=$value['izc']['naam'];
			
			if (empty($key)) {
				$key="Onbekend";
			}
			
			$cnt=$value[0]['cnt'];
			
			$results[$key]=array(
				'title' => $key,
				'data' => array('Totaal' => $cnt),
			);
			
		}
		
		return $results;
	}
	public function aanvullend_binnengekomen_html($startDate, $endDate, $labels)
	{
		$results=array();
		
		$sql = "select izv.naam, count(*) as cnt from iz_deelnemers izd "
			." left join iz_via_personen izv on izv.id = binnengekomen_via	"
			." where model = 'Vrijwilliger' "
			." and datum_aanmelding >=	'".$startDate."' "
			." and datum_aanmelding <= '".$endDate."' "
			." group by izv.id";
		
		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$key=$value['izv']['naam'];
			if (empty($key)) {
				$key="Onbekend";
			}
			
			$cnt=$value[0]['cnt'];
			
			$results[$key]=array(
				'title' => $key,
				'data' => array('Totaal' => $cnt),
			);
		}
		
		return $results;
	}
	
	public function aanvullend_aanmelding_html($startDate, $endDate, $labels)
	{
		$results=array();
		
		foreach ($labels as $key => $value) {
			
			if (empty($key)) {
				continue;
			}
			if ($key == 'Totaal') {
				continue;
			}
			$results[$value]=array(
				'title' => $value,
				'data' => array('Totaal' => 0),
			);
			
		}
		
		$sql = 'SELECT izp.naam, COUNT(*) AS cnt'
			 .' FROM iz_deelnemers izd'
			 .' JOIN vrijwilligers v ON v.id = izd.foreign_key AND izd.model = "Vrijwilliger"'
			 .' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id'
			 .' JOIN iz_projecten izp on izp.id = izk.project_id '
			 .' AND izk.startdatum < "'.$endDate.'"'
			 .' AND izk.startdatum > "'.$startDate.'"'
			 .' GROUP BY izk.project_id';

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$key=$value['izp']['naam'];
			
			if (empty($key)) {
				$key="Onbekend";
			}
			
			$cnt=$value[0]['cnt'];
			
			$results[$key]=array(
				'title' => $key,
				'data' => array('Totaal' => $cnt),
			);
			
		}
		
		return $results;
	}
	public function A1_new_per_project_per_werkgebied($startDate, $endDate, $werkgebieden)
	{
		$results = array();
		$template=array();
		
		foreach ($werkgebieden as $key => $value) {
			$template[ $key ] = 0;
		}

		$sql="SELECT p.naam AS Project, k.werkgebied AS Werkgebied, COUNT(*) AS `Aantal` 
FROM iz_deelnemers izd 
JOIN klanten k ON k.id = izd.foreign_key AND izd.model = 'Klant' 
JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id 
JOIN iz_projecten p on p.id = izk.project_id
WHERE izk.iz_koppeling_id IS NOT NULL 
AND (izk.iz_eindekoppeling_id IS NULL OR izk.iz_eindekoppeling_id !=10) AND izk.koppeling_startdatum >= '{$startDate}' AND izk.koppeling_startdatum <= '{$endDate}' 
GROUP BY izk.project_id, k.werkgebied
ORDER BY p.naam, k.werkgebied";

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$key=$value['p']['Project'];
			$w=$value['k']['Werkgebied'];
			$a=$value[0]['Aantal'];
			
			if (empty($results[$key])) {
				$results[$key] = array(
					'title' => $key,
					'data'=> $template,
				);
			}
			
			if (isset($results[$key]['data'][$w])) {
				$results[$key]['data'][$w]+=$a;
			} else {
				$results[$key]['data']['']+=$a;
			}
		}

		$all=0;
		
		foreach ($results as $key => $result) {
			
			$tot = 0;
			
			foreach ($result['data'] as $k => $value) {
				$tot+= $value;
				$all+=$value;
				$template[$k]+=$value;
			}
			
			$results[$key]['data']['Totaal']=$tot;
			
		}

		$template['Totaal']=$all;
		
		$results[]=array(
			'title' => 'Totaal',
			'data' => $template,
		);
		
		return $results;
	}
	
	public function A2_new_per_project_per_werkgebied_totaal($startDate, $endDate, $labels)
	{
		$results=array();
		
		$sql="select kl.Project, kl.Werkgebied, kl.Klant, kl.koppeling_startdatum, kl.koppeling_einddatum, kl.iz_koppeling_id, izk.iz_deelnemer_id, izd.foreign_key, 
CONCAT_WS(' ', v.voornaam, v.tussenvoegsel, v.achternaam) AS `Vrijwilliger`
from
(SELECT p.naam AS Project, k.werkgebied AS Werkgebied, CONCAT_WS(' ', k.voornaam, k.tussenvoegsel, k.achternaam) AS `Klant`, izk.koppeling_startdatum, izk.koppeling_einddatum, iz_koppeling_id
FROM iz_deelnemers izd JOIN klanten k ON k.id = izd.foreign_key AND izd.model = 'Klant' 
JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id JOIN iz_projecten p on p.id = izk.project_id WHERE izk.iz_koppeling_id IS NOT NULL 
AND (izk.iz_eindekoppeling_id IS NULL OR izk.iz_eindekoppeling_id !=10) AND izk.koppeling_startdatum >= '{$startDate}' AND izk.koppeling_startdatum <= '{$endDate}' ORDER BY p.naam, k.werkgebied, izk.koppeling_startdatum ) as kl 
join iz_koppelingen izk on izk.id = kl.iz_koppeling_id 
left join iz_deelnemers izd on izd.id = izk.iz_deelnemer_id and izd.model = 'Vrijwilliger' 
join vrijwilligers v on v.id = foreign_key ";

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$results[]=array(
				'title' => $value['kl']['Project'],
				'data' => array(
					'Werkgebied' => $value['kl']['Werkgebied'],
					'Klant' => $value['kl']['Klant'],
					'Vrijwilliger' => $value[0]['Vrijwilliger'],
					'koppeling_startdatum' => $value['kl']['koppeling_startdatum'],
					'koppeling_einddatum' => $value['kl']['koppeling_einddatum'],
				),
			);
			
		}

		return $results;
	}
	
	public function B1_stopped_per_project_per_werkgebied($startDate, $endDate, $werkgebieden)
	{
		$results=array();
		$template=array();
		
		foreach ($werkgebieden as $key => $value) {
			$template[ $key ] = 0;
		}

		$sql="SELECT p.naam AS Project, k.werkgebied AS Werkgebied, COUNT(*) AS `Aantal` FROM iz_deelnemers izd JOIN 
	klanten k ON k.id = izd.foreign_key AND izd.model = 'Klant' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id JOIN
	iz_projecten p on p.id = izk.project_id WHERE izk.iz_koppeling_id IS NOT NULL AND 
	izk.koppeling_einddatum >= '{$startDate}' AND izk.koppeling_einddatum <= '{$endDate}' AND
	izk.iz_eindekoppeling_id != 10 GROUP BY izk.project_id, k.werkgebied ORDER BY p.naam, k.werkgebied";

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$key=$value['p']['Project'];
			$w=$value['k']['Werkgebied'];
			$a=$value[0]['Aantal'];
			
			if (empty($results[$key])) {
				$results[$key] = array(
					'title' => $key,
					'data'=> $template,
				);
			}
			
			if (isset($results[$key]['data'][$w])) {
				$results[$key]['data'][$w]+=$a;
			} else {
				$results[$key]['data']['']+=$a;
			}
		}
		
		$all=0;
		
		foreach ($results as $key => $result) {
			
			$tot = 0;
			
			foreach ($result['data'] as $k => $value) {
				
				$tot+= $value;
				$all+=$value;
				$template[$k]+=$value;
				
			}
			
			$results[$key]['data']['Totaal']=$tot;
		}

		$template['Totaal']=$all;
		
		$results[]=array(
			'title' => 'Totaal',
			'data' => $template,
		);

		return $results;
	}
	public function B2_stopped_per_project_per_werkgebied_totaal($startDate, $endDate, $labels)
	{
		$results=array();
		
		$sql="select kl.Project, kl.Werkgebied, kl.Klant, kl.koppeling_startdatum, kl.koppeling_einddatum, kl.iz_koppeling_id , izk.iz_deelnemer_id, izd.foreign_key, 
CONCAT_WS(' ', v.voornaam, v.tussenvoegsel, v.achternaam) AS `Vrijwilliger`
from
(SELECT p.naam AS Project, k.werkgebied AS Werkgebied, CONCAT_WS(' ', k.voornaam, k.tussenvoegsel, k.achternaam) AS `Klant`, izk.koppeling_startdatum,	izk.koppeling_einddatum, iz_koppeling_id
FROM iz_deelnemers izd JOIN klanten k ON k.id = izd.foreign_key AND izd.model = 'Klant' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id 
JOIN iz_projecten p on p.id = izk.project_id WHERE izk.iz_koppeling_id IS NOT NULL AND 
	izk.koppeling_einddatum >= '{$startDate}' AND izk.koppeling_einddatum <= '{$endDate}'
AND izk.iz_eindekoppeling_id != 10 ORDER BY p.naam, k.werkgebied, izk.koppeling_einddatum) as kl  
join iz_koppelingen izk on izk.id = kl.iz_koppeling_id 
left join iz_deelnemers izd on izd.id = izk.iz_deelnemer_id and izd.model = 'Vrijwilliger' 
join vrijwilligers v on v.id = foreign_key ";

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$results[]=array(
				'title' => $value['kl']['Project'],
				'data' => array(
					'Werkgebied' => $value['kl']['Werkgebied'],
					'Klant' => $value['kl']['Klant'],
					'Vrijwilliger' => $value[0]['Vrijwilliger'],
					'koppeling_startdatum' => $value['kl']['koppeling_startdatum'],
					'koppeling_einddatum' => $value['kl']['koppeling_einddatum'],
				),
			);
		}

		return $results;
	}
	
	public function C1_geslaagd_per_project_per_werkgebied($startDate, $endDate, $werkgebieden)
	{
		$results = array();
		$template=array();
		
		foreach ($werkgebieden as $key => $value) {
			$template[ $key ] = 0;
		}
		
		$sql="SELECT p.naam AS Project, k.werkgebied AS Werkgebied, COUNT(*) AS `Aantal` FROM iz_deelnemers izd 
JOIN klanten k ON k.id = izd.foreign_key AND izd.model = 'Klant' JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id JOIN iz_projecten p on p.id = izk.project_id WHERE 
	izk.iz_koppeling_id IS NOT NULL AND 
	izk.koppeling_einddatum >= '{$startDate}' AND izk.koppeling_einddatum <= '{$endDate}'
AND izk.iz_eindekoppeling_id != 10 AND izk.koppeling_succesvol = 1 GROUP BY izk.project_id, k.werkgebied ORDER BY p.naam, k.werkgebied";

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$key=$value['p']['Project'];
			$w=$value['k']['Werkgebied'];
			$a=$value[0]['Aantal'];
			
			if (empty($results[$key])) {
				$results[$key] = array(
					'title' => $key,
					'data'=> $template,
				);
			}
			
			if (isset($results[$key]['data'][$w])) {
				$results[$key]['data'][$w]+=$a;
			} else {
				$results[$key]['data']['']+=$a;
			}
		}
		
		$all=0;
		
		foreach ($results as $key => $result) {
			
			$tot = 0;
			
			foreach ($result['data'] as $k => $value) {
				$tot+= $value;
				$all+=$value;
				$template[$k]+=$value;
			}
			
			$results[$key]['data']['Totaal']=$tot;
		}

		$template['Totaal']=$all;
		
		$results[]=array(
			'title' => 'Totaal',
			'data' => $template,
		);

		return $results;
	}
	public function C2_geslaagd_per_project_per_werkgebied_totaal($startDate, $endDate, $werkgebieden)
	{
		$results = array();
		
		$sql="select kl.Project, kl.Werkgebied, kl.Klant, kl.koppeling_startdatum, kl.koppeling_einddatum, kl.iz_koppeling_id, izk.iz_deelnemer_id, izd.foreign_key, 
CONCAT_WS(' ', v.voornaam, v.tussenvoegsel, v.achternaam) AS `Vrijwilliger`
 from
(SELECT p.naam AS Project, k.werkgebied AS Werkgebied, CONCAT_WS(' ', k.voornaam, k.tussenvoegsel, k.achternaam) AS `Klant`,
	izk.koppeling_startdatum, izk.koppeling_einddatum, iz_koppeling_id FROM iz_deelnemers izd JOIN klanten k ON k.id = izd.foreign_key AND izd.model = 'Klant' 
JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id JOIN iz_projecten p on p.id = izk.project_id WHERE izk.iz_koppeling_id IS NOT NULL 
AND izk.koppeling_einddatum >= '{$startDate}' AND izk.koppeling_einddatum <= '{$endDate}'
AND izk.iz_eindekoppeling_id != 10 AND izk.koppeling_succesvol = 1 ORDER BY p.naam, k.werkgebied, izk.koppeling_einddatum) as kl 
join iz_koppelingen izk on izk.id = kl.iz_koppeling_id 
left join iz_deelnemers izd on izd.id = izk.iz_deelnemer_id and izd.model = 'Vrijwilliger' 
join vrijwilligers v on v.id = foreign_key ";

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$results[]=array(
				'title' => $value['kl']['Project'],
				'data' => array(
					'Werkgebied' => $value['kl']['Werkgebied'],
					'Klant' => $value['kl']['Klant'],
					'Vrijwilliger' => $value[0]['Vrijwilliger'],
					'koppeling_startdatum' => $value['kl']['koppeling_startdatum'],
					'koppeling_einddatum' => $value['kl']['koppeling_einddatum'],
				),
			);
			
		}

		return $results;
	}
	
	public function F1_nieuwe_vrijwilligers_per_project_per_werkgebied($startDate, $endDate, $werkgebieden)
	{
		$results = array();
		$template=array();
		
		foreach ($werkgebieden as $key => $value) {
			$template[ $key ] = 0;
		}

		$sql="SELECT p.naam AS Project, k.werkgebied AS Werkgebied, COUNT(*) AS `Aantal` 
FROM iz_deelnemers izd 
JOIN vrijwilligers k ON k.id = izd.foreign_key AND izd.model = 'Vrijwilliger' 
JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id 
JOIN iz_projecten p on p.id = izk.project_id
WHERE izk.iz_koppeling_id IS NOT NULL 
AND (izk.iz_eindekoppeling_id IS NULL OR izk.iz_eindekoppeling_id !=10) AND izk.koppeling_startdatum >= '{$startDate}' AND izk.koppeling_startdatum <= '{$endDate}' 
GROUP BY k.id, izk.project_id, k.werkgebied
ORDER BY p.naam, k.werkgebied";

		$data = $this->query($sql);
		foreach ($data as $value) {
			$key=$value['p']['Project'];
			$w=$value['k']['Werkgebied'];
			$a=$value[0]['Aantal'];
			if (empty($results[$key])) {
				$results[$key] = array(
					'title' => $key,
					'data'=> $template,
				);
			}
			if (isset($results[$key]['data'][$w])) {
				$results[$key]['data'][$w]+=$a;
			} else {
				$results[$key]['data']['']+=$a;
			}
		}

		$all=0;
		
		foreach ($results as $key => $result) {
			
			$tot = 0;
			
			foreach ($result['data'] as $k => $value) {
				$tot+= $value;
				$all+=$value;
				$template[$k]+=$value;
			}
			
			$results[$key]['data']['Totaal']=$tot;
		}

		$template['Totaal']=$all;
		
		$results[]=array(
			'title' => 'Totaal',
			'data' => $template,
		);
		
		return $results;
	}

	public function F2_namen_nieuwe_vrijwilligers_per_project_per_werkgebied($startDate, $endDate, $labels)
	{
		
		$results = array();
		
		$sql="SELECT p.naam AS Project, k.werkgebied AS Werkgebied, CONCAT_WS(' ', k.voornaam, k.tussenvoegsel, k.achternaam) AS `Klant`, izk.koppeling_startdatum, izk.koppeling_einddatum
FROM iz_deelnemers izd JOIN vrijwilligers k ON k.id = izd.foreign_key AND izd.model = 'Vrijwilliger' 
JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id JOIN iz_projecten p on p.id = izk.project_id WHERE izk.iz_koppeling_id IS NOT NULL 
AND (izk.iz_eindekoppeling_id IS NULL OR izk.iz_eindekoppeling_id !=10) AND izk.koppeling_startdatum >= '{$startDate}' AND izk.koppeling_startdatum <= '{$endDate}' GROUP BY k.id ORDER BY p.naam, k.werkgebied, izk.koppeling_startdatum";

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$results[]=array(
				'title' => $value['p']['Project'],
				'data' => array(
					'Werkgebied' => $value['k']['Werkgebied'],
					'Klant' => $value[0]['Klant'],
					'koppeling_startdatum' => $value['izk']['koppeling_startdatum'],
					'koppeling_einddatum' => $value['izk']['koppeling_einddatum'],
				),
			);
		}

		return $results;
	}
	public function J1_nieuwe_deelnemers_per_project_per_werkgebied($startDate, $endDate, $werkgebieden)
	{
		$results = array();
		$template=array();
		
		foreach ($werkgebieden as $key => $value) {
			$template[ $key ] = 0;
		}

		$sql="SELECT p.naam AS Project, k.werkgebied AS Werkgebied, COUNT(*) AS `Aantal` 
FROM iz_deelnemers izd 
JOIN klanten k ON k.id = izd.foreign_key AND izd.model = 'Klant' 
JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id 
JOIN iz_projecten p on p.id = izk.project_id
WHERE izk.iz_koppeling_id IS NOT NULL 
AND (izk.iz_eindekoppeling_id IS NULL OR izk.iz_eindekoppeling_id !=10) AND izk.koppeling_startdatum >= '{$startDate}' AND izk.koppeling_startdatum <= '{$endDate}' 
GROUP BY k.id, izk.project_id, k.werkgebied
ORDER BY p.naam, k.werkgebied";

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$key=$value['p']['Project'];
			$w=$value['k']['Werkgebied'];
			$a=$value[0]['Aantal'];
			
			if (empty($results[$key])) {
				
				$results[$key] = array(
					'title' => $key,
					'data'=> $template,
				);
				
			}
			
			if (isset($results[$key]['data'][$w])) {
				$results[$key]['data'][$w]+=$a;
			} else {
				$results[$key]['data']['']+=$a;
			}
		}

		$all=0;
		
		foreach ($results as $key => $result) {
			
			$tot = 0;
			
			foreach ($result['data'] as $k => $value) {
				$tot+= $value;
				$all+=$value;
				$template[$k]+=$value;
			}
			
			$results[$key]['data']['Totaal']=$tot;
		}

		$template['Totaal']=$all;
		
		$results[]=array(
			'title' => 'Totaal',
			'data' => $template,
		);
		
		return $results;
	}

	public function J2_namen_nieuwe_deelnemers_per_project_per_werkgebied($startDate, $endDate, $labels)
	{
		$results = array();
		
		$sql="SELECT p.naam AS Project, k.werkgebied AS Werkgebied, CONCAT_WS(' ', k.voornaam, k.tussenvoegsel, k.achternaam) AS `Klant`, izk.koppeling_startdatum, izk.koppeling_einddatum
FROM iz_deelnemers izd JOIN klanten k ON k.id = izd.foreign_key AND izd.model = 'Klant' 
JOIN iz_koppelingen izk ON izk.iz_deelnemer_id = izd.id JOIN iz_projecten p on p.id = izk.project_id WHERE izk.iz_koppeling_id IS NOT NULL 
AND (izk.iz_eindekoppeling_id IS NULL OR izk.iz_eindekoppeling_id !=10) AND izk.koppeling_startdatum >= '{$startDate}' AND izk.koppeling_startdatum <= '{$endDate}' GROUP BY k.id ORDER BY p.naam, k.werkgebied, izk.koppeling_startdatum";

		$data = $this->query($sql);
		
		foreach ($data as $value) {
			
			$results[]=array(
				'title' => $value['p']['Project'],
				'data' => array(
					'Werkgebied' => $value['k']['Werkgebied'],
					'Klant' => $value[0]['Klant'],
					'koppeling_startdatum' => $value['izk']['koppeling_startdatum'],
					'koppeling_einddatum' => $value['izk']['koppeling_einddatum'],
				),
			);
		}

		return $results;
	}
	public function K1_nieuwe_deelnemers_per_per_werkgebied_zonder_intake($startDate, $endDate, $labels)
	{
		$results = array();

		$sql="select k.werkgebied as Werkgebied, count(*) as Aantal from iz_deelnemers izd left join iz_intakes i on i.iz_deelnemer_id = izd.id 
join klanten k on k.id = izd.foreign_key and izd.model = 'Klant' 
where izd.model = 'Klant' and not isnull(izd.datum_aanmelding) and isnull(i.intake_datum)  
AND izd.datum_aanmelding >= '{$startDate}' AND izd.datum_aanmelding <= '{$endDate}'  group by k.werkgebied ";

		$data = $this->query($sql);
		
		$total = 0;
		
		foreach ($data as $value) {
			
			$w=$value['k']['Werkgebied'];
			$a=$value[0]['Aantal'];
			
			$results[] = array(
				'title' => $w,
				'data'=> array('totaal' => $a),
			);
			
			$total += $a;
		}
		
		$results[] = array(
			'title' => 'Totaal',
			'data'=> array('totaal' => $total),
		);
		
		return $results;
	}
	public function K2_namen_nieuwe_deelnemers_per_per_werkgebied_zonder_intake($startDate, $endDate, $labels)
	{
		$results = array();
		$sql="select CONCAT_WS(' ', k.voornaam, k.tussenvoegsel, k.achternaam) AS `Klant`, k.werkgebied as Werkgebied, datum_aanmelding from iz_deelnemers izd left join iz_intakes i on i.iz_deelnemer_id = izd.id 
join klanten k on k.id = izd.foreign_key and izd.model = 'Klant' 
where izd.model = 'Klant' and not isnull(izd.datum_aanmelding) and isnull(i.intake_datum)  
AND izd.datum_aanmelding >= '{$startDate}' AND izd.datum_aanmelding <= '{$endDate}'  order by werkgebied";

		$data = $this->query($sql);
		
		$total = 0;
		
		foreach ($data as $value) {
			
			$w=$value['k']['Werkgebied'];
			$k=$value[0]['Klant'];
			
			$results[] = array(
				'title' => $w,
				'data'=> array(
					'Klant' => $k,
					'datum_aanmelding' => $value['izd']['datum_aanmelding'],
				 ),
			);
			$total ++;
			
		}
		
		$results[] = array(
			'title' => 'Totaal',
			'data'=> array(
				'Klant' => $total,
				'datum_aanmelding' => "",
			 ),
		);

		return $results;
		
	}
	public function L1_nieuwe_deelnemers_per_per_werkgebied_zonder_aanbod($startDate, $endDate, $labels)
	{
		$results = array();

		$sql="select werkgebied as Werkgebied, count(*) as Aantal from 
			(select izd.id, model, foreign_key, datum_aanmelding, izk.id as iz_koppeling_id from iz_deelnemers izd 
			left join iz_koppelingen izk on izk.iz_deelnemer_id = izd.id join iz_intakes i on i.iz_deelnemer_id = izd.id 
			and not isnull(intake_datum) 
			where izd.datum_aanmelding >= '{$startDate}' and izd.datum_aanmelding <= '{$endDate}' 
			and model = 'Klant' group by izd.id having isnull(izk.id)) as s 
			join klanten k on k.id = s.foreign_key and model = 'Klant' group by werkgebied";

		$data = $this->query($sql);
		$total = 0;
		
		foreach ($data as $value) {
			
			$w=$value['k']['Werkgebied'];
			$a=$value[0]['Aantal'];
			
			$results[] = array(
				'title' => $w,
				'data'=> array('totaal' => $a),
			);
			
			$total += $a;
		}
		
		$results[] = array(
			'title' => 'Totaal',
			'data'=> array('totaal' => $total),
		);
		
		return $results;
	}
	public function L2_namen_nieuwe_deelnemers_per_per_werkgebied_zonder_aanbod($startDate, $endDate, $labels)
	{
		$results = array();
		$sql="select werkgebied as Werkgebied, CONCAT_WS(' ', k.voornaam, k.tussenvoegsel, k.achternaam) AS `Klant`, s.datum_aanmelding, s.intake_datum, s.medewerker_id, CONCAT_WS(' ', m.voornaam, m.tussenvoegsel, m.achternaam)  as medewerker	from 
			(select izd.id, model, foreign_key, datum_aanmelding, izk.id as iz_koppeling_id, i.intake_datum, i.medewerker_id  from iz_deelnemers izd 
			left join iz_koppelingen izk on izk.iz_deelnemer_id = izd.id join iz_intakes i on i.iz_deelnemer_id = izd.id 
			and not isnull(intake_datum) 
			where izd.datum_aanmelding >= '{$startDate}' and izd.datum_aanmelding <= '{$endDate}' 
			and model = 'Klant' group by izd.id having isnull(izk.id)) as s 
			join klanten k on k.id = s.foreign_key and model = 'Klant' left join medewerkers m on m.id = s.medewerker_id ";

		$data = $this->query($sql);
		$total = 0;
		
		foreach ($data as $value) {
			
			$w=$value['k']['Werkgebied'];
			$k=$value[0]['Klant'];
			
			$results[] = array(
				'title' => $w,
				'data'=> array(
					'Klant' => $k,
					'datum_aanmelding' => $value['s']['datum_aanmelding'],
					'intake_datum' => $value['s']['intake_datum'],
					'medewerker' => $value[0]['medewerker'],
				 ),
			);
			$total ++;
		}
		
		$results[] = array(
			'title' => 'Totaal',
			'data'=> array(
				'Klant' => $total,
				'datum_aanmelding' => "",
				'intake_datum' => "",
				'medewerker' => "",
			 ),
		);

		return $results;
	}
}
