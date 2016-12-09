<?php

class ViewIzKoppeling extends AppModel
{
    public $name = 'ViewIzKoppeling';

    public $useTable = 'view_iz_koppelingen';

    public $displayField = 'iz_deelnemer_id';

// 	public $actsAs = array('Containable');

// 	public $hasOne = array(
// 		'Koppeling' => array(
// 			'className' => 'IzKoppeling',
// 			'foreignKey' => 'iz_koppeling_id',
// 		),
// 	);

// 	public $belongsTo = array(
// 		'IzDeelnemer' => array(
// 			'className' => 'IzDeelnemer',
// 			'foreignKey' => 'iz_deelnemer_id',
// 			'conditions' => array('model' => 'Klant'),
// 		),
// 		'IzProject' => array(
// 			'className' => 'IzProject',
// 			'foreignKey' => 'project_id',
// 		),
// 		'Medewerker' => array(
// 			'className' => 'Medewerker',
// 			'foreignKey' => 'medewerker_id',
// 		),
// 		'IzEindekoppeling' => array(
// 			'className' => 'IzEindekoppeling',
// 			'foreignKey' => 'iz_eindekoppeling_id',
// 		),
// 		'IzVraagaanbod' => array(
// 			'className' => 'IzVraagaanbod',
// 			'foreignKey' => 'iz_vraagaanbod_id',
// 		),
// 	);

// 	public $validate = array(
// 		'medewerker_id' => array(
// 			'notempty' => array(
// 				'rule' => array('notEmpty'),
// 				'message' => 'Voer een medewerker in',
// 				'allowEmpty' => false,
// 				'required' => false,
// 			),
// 		),
// 		'iz_vraagaanbod_id' => array(
// 			'notempty' => array(
// 				'rule' => array('notEmpty'),
// 				'message' => 'Voer een reden in',
// 				'allowEmpty' => false,
// 				'required' => false,
// 			),
// 		),
// 		'iz_eindekoppeling_id' => array(
// 			'notempty' => array(
// 				'rule' => array('notEmpty'),
// 				'message' => 'Voer een reden in',
// 				'allowEmpty' => false,
// 				'required' => false,
// 			),
// 		),
// 		'startdatum' => array(
// 			'notempty' => array(
// 				'rule' => array('notEmpty'),
// 				'message' => 'Voer een startdatum in',
// 				'allowEmpty' => false,
// 				'required' => false,
// 			),
// 		),
// 		'iz_koppeling_id' => array(
// 			'notempty' => array(
// 				'rule' => array('notEmpty'),
// 				'message' => 'Selecteer een koppeling',
// 				'allowEmpty' => false,
// 				'required' => false,
// 			),
// 		),
// 		'koppelling_startdatum' => array(
// 			'notempty' => array(
// 				'rule' => array('notEmpty'),
// 				'message' => 'Voer een startdatum in',
// 				'allowEmpty' => false,
// 				'required' => false,
// 			),
// 		),
// 		'project_id' => array(
// 			'notempty' => array(
// 				'rule' => array('notEmpty'),
// 				'message' => 'Voer een project in',
// 				'allowEmpty' => false,
// 				'required' => false,
// 			),
// 		),
// 	);

// 	public function beforeFind($queryData)
// 	{
// 		if ($this instanceof IzHulpaanbod) {
// 			$queryData['conditions'][] = array('IzVrijwilliger.id IS NOT NULL');
// 		} elseif ($this instanceof IzHulpvraag) {
// 			$queryData['conditions'][] = array('IzKlant.id IS NOT NULL');
// 		} else {
// 			$queryData['conditions'][] = array('IzKlant.id IS NOT NULL');
// 			$queryData['conditions'][] = array('Koppeling.id IS NOT NULL');
// 		}

// 		return $queryData;
// 	}

// 	public function getCandidatesForProjects($persoon_model, $project_ids)
// 	{
// 		if ($persoon_model == 'Klant') {
// 			$model = 'Vrijwilliger';
// 		} else {
// 			$model = 'Klant';
// 		}

// 		$contain = array(
// 			'IzDeelnemer',
// 		);

// 		$today=date('Y-m-d');

// 		$conditions = array(
// 			'IzDeelnemer.model' => $model,
// 			'IzKoppeling.iz_koppeling_id' => null,
// 			'IzKoppeling.project_id' => $project_ids,
// 			array(
// 			'OR' => array(
// 					'IzKoppeling.einddatum' => null,
// 					'IzKoppeling.einddatum >=' =>  $today,
// 				),
// 			),
// 			array(
// 				'OR' => array(
// 					'IzKoppeling.startdatum' => null,
// 					'IzKoppeling.startdatum <=' => $today,
// 				),
// 			),
// 		);

// 		$all = $this->find('all', array(
// 			'conditions' => $conditions,
// 			'contain' => $contain,
// 		));

// 		foreach ($all as $key => $a) {
// 			$all[$key][$model] = $this->IzDeelnemer->{$model}->getById($a['IzDeelnemer']['foreign_key']);
// 		}

// 		$projects = [];

// 		foreach ($project_ids as $p_id) {
// 			$projects[$p_id] = [];
// 		}

// 		foreach ($all as $a) {
// 			$projects[$a['IzKoppeling']['project_id']][$a['IzKoppeling']['id']]=$a[$model]['name'];
// 		}

// 		return $projects;
// 	}

// 	public function koppelingen_per_project_beginstand(DateTime $startDate)
// 	{
// 		$sql = $this->koppelingen_per_project(
// 			$this->koppelingen_beginstand($startDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_project_gestart(DateTime $startDate, DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_project(
// 			$this->koppelingen_gestart($startDate, $endDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_project_gestopt(DateTime $startDate, DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_project(
// 			$this->koppelingen_gestopt($startDate, $endDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_project_eindstand(DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_project(
// 			$this->koppelingen_eindstand($endDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_werkgebied_beginstand(DateTime $startDate)
// 	{
// 		$sql = $this->koppelingen_per_werkgebied(
// 			$this->koppelingen_beginstand($startDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 's.stadsdeel', '0.aantal');
// 	}

// 	public function koppelingen_per_werkgebied_gestart(DateTime $startDate, DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_werkgebied(
// 			$this->koppelingen_gestart($startDate, $endDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 's.stadsdeel', '0.aantal');
// 	}

// 	public function koppelingen_per_werkgebied_gestopt(DateTime $startDate, DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_werkgebied(
// 			$this->koppelingen_gestopt($startDate, $endDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 's.stadsdeel', '0.aantal');
// 	}

// 	public function koppelingen_per_werkgebied_eindstand(DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_werkgebied(
// 			$this->koppelingen_eindstand($endDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 's.stadsdeel', '0.aantal');
// 	}

// 	public function koppelingen_per_postcodegebied_beginstand(DateTime $startDate)
// 	{
// 		$sql = $this->koppelingen_per_postcodegebied(
// 			$this->koppelingen_beginstand($startDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 'pc.postcodegebied', '0.aantal');
// 	}

// 	public function koppelingen_per_postcodegebied_gestart(DateTime $startDate, DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_postcodegebied(
// 			$this->koppelingen_gestart($startDate, $endDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 'pc.postcodegebied', '0.aantal');
// 	}

// 	public function koppelingen_per_postcodegebied_gestopt(DateTime $startDate, DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_postcodegebied(
// 			$this->koppelingen_gestopt($startDate, $endDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 'pc.postcodegebied', '0.aantal');
// 	}

// 	public function koppelingen_per_postcodegebied_eindstand(DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_postcodegebied(
// 			$this->koppelingen_eindstand($endDate)
// 		);
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, null, 'pc.postcodegebied', '0.aantal');
// 	}

// 	public function koppelingen_per_project_werkgebied_beginstand(DateTime $startDate)
// 	{
// 		$sql = $this->koppelingen_per_project_werkgebied($this->koppelingen_beginstand($startDate));
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_project_werkgebied_gestart(DateTime $startDate, DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_project_werkgebied($this->koppelingen_gestart($startDate, $endDate));
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_project_werkgebied_gestopt(DateTime $startDate, DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_project_werkgebied($this->koppelingen_gestopt($startDate, $endDate));
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_project_werkgebied_eindstand(DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_project_werkgebied($this->koppelingen_eindstand($endDate));
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_project_postcodegebied_beginstand(DateTime $startDate)
// 	{
// 		$sql = $this->koppelingen_per_project_postcodegebied($this->koppelingen_beginstand($startDate));
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_project_postcodegebied_gestart(DateTime $startDate, DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_project_postcodegebied($this->koppelingen_gestart($startDate, $endDate));
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_project_postcodegebied_gestopt(DateTime $startDate, DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_project_postcodegebied($this->koppelingen_gestopt($startDate, $endDate));
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
// 	}

// 	public function koppelingen_per_project_postcodegebied_eindstand(DateTime $endDate)
// 	{
// 		$sql = $this->koppelingen_per_project_postcodegebied($this->koppelingen_eindstand($endDate));
// 		$result = $this->query($sql);

// 		return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
// 	}

// 	private function koppelingen_per_project($condition)
// 	{
// 		return "SELECT COUNT(DISTINCT vraag.id) AS aantal, p.naam AS project
// 			FROM iz_koppelingen vraag
// 			INNER JOIN iz_projecten p ON p.id = vraag.project_id
// 			INNER JOIN iz_deelnemers izklant ON izklant.id = vraag.iz_deelnemer_id
// 			AND izklant.model = 'Klant'
// 			AND (izklant.iz_afsluiting_id IS NULL OR izklant.iz_afsluiting_id != 10)
// 			INNER JOIN klanten ON izklant.foreign_key = klanten.id
// 			INNER JOIN iz_koppelingen aanbod ON aanbod.id = vraag.iz_koppeling_id
// 			INNER JOIN iz_deelnemers izvrijwilliger ON izvrijwilliger.id = aanbod.iz_deelnemer_id
// 			AND izvrijwilliger.model = 'Vrijwilliger'
// 			AND (izvrijwilliger.iz_afsluiting_id IS NULL OR izvrijwilliger.iz_afsluiting_id != 10)
// 			INNER JOIN vrijwilligers ON izvrijwilliger.foreign_key = vrijwilligers.id
// 			{$condition}
// 			GROUP BY p.naam";
// 	}

// 	private function koppelingen_per_werkgebied($condition)
// 	{
// 		return "SELECT COUNT(DISTINCT vraag.id) AS aantal, s.stadsdeel
// 			FROM iz_koppelingen vraag
// 			INNER JOIN iz_deelnemers izklant ON izklant.id = vraag.iz_deelnemer_id
// 			AND izklant.model = 'Klant'
// 			AND (izklant.iz_afsluiting_id IS NULL OR izklant.iz_afsluiting_id != 10)
// 			INNER JOIN klanten ON izklant.foreign_key = klanten.id
// 			LEFT JOIN stadsdelen s ON s.postcode = klanten.postcode
// 			INNER JOIN iz_koppelingen aanbod ON aanbod.id = vraag.iz_koppeling_id
// 			INNER JOIN iz_deelnemers izvrijwilliger ON izvrijwilliger.id = aanbod.iz_deelnemer_id
// 			AND izvrijwilliger.model = 'Vrijwilliger'
// 			AND (izvrijwilliger.iz_afsluiting_id IS NULL OR izvrijwilliger.iz_afsluiting_id != 10)
// 			INNER JOIN vrijwilligers ON izvrijwilliger.foreign_key = vrijwilligers.id
// 			{$condition}
// 			GROUP BY s.stadsdeel";
// 	}

// 	private function koppelingen_per_postcodegebied($condition)
// 	{
// 		return "SELECT COUNT(DISTINCT vraag.id) AS aantal, pc.postcodegebied
// 			FROM iz_koppelingen vraag
// 			INNER JOIN iz_deelnemers izklant ON izklant.id = vraag.iz_deelnemer_id
// 				AND izklant.model = 'Klant'
// 				AND (izklant.iz_afsluiting_id IS NULL OR izklant.iz_afsluiting_id != 10)
// 			INNER JOIN klanten ON izklant.foreign_key = klanten.id
// 			LEFT JOIN postcodegebieden pc ON SUBSTRING(klanten.postcode, 1, 4) BETWEEN pc.van AND pc.tot
// 			INNER JOIN iz_koppelingen aanbod ON aanbod.id = vraag.iz_koppeling_id
// 			INNER JOIN iz_deelnemers izvrijwilliger ON izvrijwilliger.id = aanbod.iz_deelnemer_id
// 				AND izvrijwilliger.model = 'Vrijwilliger'
// 				AND (izvrijwilliger.iz_afsluiting_id IS NULL OR izvrijwilliger.iz_afsluiting_id != 10)
// 			INNER JOIN vrijwilligers ON izvrijwilliger.foreign_key = vrijwilligers.id
// 			{$condition}
// 			GROUP BY pc.postcodegebied";
// 	}

// 	private function koppelingen_per_project_werkgebied($condition)
// 	{
// 		return "SELECT COUNT(DISTINCT vraag.id) AS aantal, p.naam AS project, s.stadsdeel
// 			FROM iz_koppelingen vraag
// 			INNER JOIN iz_projecten p ON p.id = vraag.project_id
// 			INNER JOIN iz_deelnemers izklant ON izklant.id = vraag.iz_deelnemer_id
// 			AND izklant.model = 'Klant'
// 			AND (izklant.iz_afsluiting_id IS NULL OR izklant.iz_afsluiting_id != 10)
// 			INNER JOIN klanten ON izklant.foreign_key = klanten.id
// 			LEFT JOIN stadsdelen s ON s.postcode = klanten.postcode
// 			INNER JOIN iz_koppelingen aanbod ON aanbod.id = vraag.iz_koppeling_id
// 			INNER JOIN iz_deelnemers izvrijwilliger ON izvrijwilliger.id = aanbod.iz_deelnemer_id
// 			AND izvrijwilliger.model = 'Vrijwilliger'
// 			AND (izvrijwilliger.iz_afsluiting_id IS NULL OR izvrijwilliger.iz_afsluiting_id != 10)
// 			INNER JOIN vrijwilligers ON izvrijwilliger.foreign_key = vrijwilligers.id
// 			{$condition}
// 			GROUP BY p.naam, s.stadsdeel";
// 	}

// 	private function koppelingen_per_project_postcodegebied($condition)
// 	{
// 		return "SELECT COUNT(DISTINCT vraag.id) AS aantal, p.naam AS project, pc.postcodegebied
// 			FROM iz_koppelingen vraag
// 			INNER JOIN iz_projecten p ON p.id = vraag.project_id
// 			INNER JOIN iz_deelnemers izklant ON izklant.id = vraag.iz_deelnemer_id
// 			AND izklant.model = 'Klant'
// 			AND (izklant.iz_afsluiting_id IS NULL OR izklant.iz_afsluiting_id != 10)
// 			INNER JOIN klanten ON izklant.foreign_key = klanten.id
// 			LEFT JOIN postcodegebieden pc ON SUBSTRING(klanten.postcode, 1, 4) BETWEEN pc.van AND pc.tot
// 			INNER JOIN iz_koppelingen aanbod ON aanbod.id = vraag.iz_koppeling_id
// 			INNER JOIN iz_deelnemers izvrijwilliger ON izvrijwilliger.id = aanbod.iz_deelnemer_id
// 			AND izvrijwilliger.model = 'Vrijwilliger'
// 			AND (izvrijwilliger.iz_afsluiting_id IS NULL OR izvrijwilliger.iz_afsluiting_id != 10)
// 			INNER JOIN vrijwilligers ON izvrijwilliger.foreign_key = vrijwilligers.id
// 			{$condition}
// 			GROUP BY p.naam, pc.postcodegebied";
// 	}

// 	private function koppelingen_beginstand(DateTime $startDate)
// 	{
// 		return "WHERE vraag.koppeling_startdatum < '{$startDate->format('Y-m-d')}'
// 			AND (vraag.koppeling_einddatum IS NULL OR vraag.koppeling_einddatum >= '{$startDate->format('Y-m-d')}')
// 			AND (vraag.iz_eindekoppeling_id IS NULL OR vraag.iz_eindekoppeling_id != 10)";
// 	}

// 	private function koppelingen_gestart(DateTime $startDate, DateTime $endDate)
// 	{
// 		return "WHERE vraag.koppeling_startdatum >= '{$startDate->format('Y-m-d')}'
// 			AND vraag.koppeling_startdatum <= '{$endDate->format('Y-m-d')}'
// 			AND (vraag.iz_eindekoppeling_id IS NULL OR vraag.iz_eindekoppeling_id != 10)";
// 	}

// 	private function koppelingen_gestopt(DateTime $startDate, DateTime $endDate)
// 	{
// 		return "WHERE vraag.koppeling_einddatum >= '{$startDate->format('Y-m-d')}'
// 			AND vraag.koppeling_einddatum <= '{$endDate->format('Y-m-d')}'
// 			AND (vraag.iz_eindekoppeling_id IS NULL OR vraag.iz_eindekoppeling_id != 10)";
// 	}

// 	private function koppelingen_eindstand(DateTime $endDate)
// 	{
// 		return "WHERE vraag.koppeling_startdatum <= '{$endDate->format('Y-m-d')}'
// 			AND (vraag.koppeling_einddatum IS NULL OR vraag.koppeling_einddatum > '{$endDate->format('Y-m-d')}')
// 			AND (vraag.iz_eindekoppeling_id IS NULL OR vraag.iz_eindekoppeling_id != 10)";
// 	}

// 	private function getPivotTableData(array $result, $xPath = null, $yPath = null, $nPath)
// 	{
// 		list($xValues, $yValues) = $this->getAxisLabels($result, $xPath, $yPath);
// 		$data = $this->initializePivotStructure($xValues, $yValues);
// 		foreach ($result as $row) {
// 			foreach ($yValues as $yValue) {
// 				if (Set::classicExtract($row, $yPath) === $yValue) {
// 					if (empty($xValues)) {
// 						$aantal = Set::classicExtract($row, $nPath);
// 						$data[$yValue]['Totaal'] += $aantal;
// 						$data['Totaal']['Totaal'] += $aantal;
// 					} else {
// 						foreach ($xValues as $xValue) {
// 							if (Set::classicExtract($row, $xPath) === $xValue) {
// 								$aantal = Set::classicExtract($row, $nPath);
// 								$data[$yValue][$xValue] += $aantal;
// 								$data[$yValue]['Totaal'] += $aantal;
// 								$data['Totaal'][$xValue] += $aantal;
// 								$data['Totaal']['Totaal'] += $aantal;
// 							}
// 						}
// 					}
// 				}
// 			}
// 		}

// 		return $data;
// 	}

// 	private function getAxisLabels(array $data, $xPath, $yPath)
// 	{
// 		$xLabels = [];
// 		$yLabels = [];
// 		foreach ($data as $row) {
// 			if ($xPath) {
// 				$xLabel = Set::classicExtract($row, $xPath);
// 				$xLabels[$xLabel] = $xLabel;
// 			}
// 			if ($yPath) {
// 				$yLabel = Set::classicExtract($row, $yPath);
// 				$yLabels[$yLabel] = $yLabel;
// 			}
// 		}
// 		sort($xLabels);
// 		sort($yLabels);

// 		return array($xLabels, $yLabels);
// 	}

// 	private function initializePivotStructure($xLabels, $yLabels)
// 	{
// 		$data = [];
// 		foreach ($yLabels as $yLabel) {
// 			foreach ($xLabels as $xLabel) {
// 				$data[$yLabel][$xLabel] = 0;
// 			}
// 			$data[$yLabel]['Totaal'] = 0;
// 		}
// 		foreach ($xLabels as $xLabel) {
// 			$data['Totaal'][$xLabel] = 0;
// 		}
// 		$data['Totaal']['Totaal'] = 0;

// 		return $data;
// 	}
}
