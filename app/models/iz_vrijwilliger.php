<?php

App::import('Model', 'IzDeelnemer');

class IzVrijwilliger extends IzDeelnemer
{
	public $name = 'IzVrijwilliger';

	public $belongsTo = array(
		'Vrijwilliger' => array(
			'className' => 'Vrijwilliger',
			'model' => 'Vrijwilliger',
			'foreignKey' => 'foreign_key',
			'type' => 'INNER',
		),
		'IzAfsluiting' => array(
			'className' => 'IzAfsluiting',
			'foreignKey' => 'iz_afsluiting_id',
		),
	);

	public $hasMany = array(
		'IzDeelnemersIzIntervisiegroep' => array(
			'className' => 'IzDeelnemersIzIntervisiegroep',
			'foreignKey' => 'iz_deelnemer_id',
			'dependent' => false,
		),
		'IzDeelnemersIzProject' => array(
			'className' => 'IzDeelnemersIzProject',
			'foreignKey' => 'iz_deelnemer_id',
			'dependent' => false,
		),
		'IzVerslag' => array(
			'className' => 'IzVerslag',
			'foreignKey' => 'iz_deelnemer_id',
			'dependent' => false,
		),
		'IzHulpaanbod' => array(
			'className' => 'IzHulpaanbod',
			'foreignKey' => 'iz_deelnemer_id',
			'dependent' => false,
			'type' => 'INNER',
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

	public function count_aanmeldingen(DateTime $startDate, DateTime $endDate)
	{
		$sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
			'vrijwilligers met aanmelding' AS fase
			FROM iz_deelnemers d
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'";
		$aanmeldingen = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
			'vrijwilligers met intake' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_intakes i ON i.iz_deelnemer_id = d.id
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'";
		$intakes = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
			'vrijwilligers met hulpaanbod' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'";
		$hulpaanbiedingen = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
			'vrijwilligers met koppeling' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
			AND koppeling.iz_koppeling_id IS NOT NULL
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'";
		$koppelingen = $this->query($sql);

		$result = array_merge($aanmeldingen, $intakes, $hulpaanbiedingen, $koppelingen);

		return $this->getPivotTableData($result, '0.fase', null, '0.aantal', false, true, false, true);
	}

	public function count_aanmeldingen_per_coordinator(DateTime $startDate, DateTime $endDate)
	{
		$sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker,
			'vrijwilligers met aanmelding' AS fase
			FROM iz_deelnemers d
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
			LEFT JOIN medewerkers ON medewerkers.id = v.medewerker_id
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
			GROUP BY medewerkers.id";
		$aanmeldingen = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker,
			'vrijwilligers met intake' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_intakes i ON i.iz_deelnemer_id = d.id
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
			LEFT JOIN medewerkers ON medewerkers.id = i.medewerker_id
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
			GROUP BY medewerkers.id";
		$intakes = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker,
			'vrijwilligers met hulpaanbod' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
			LEFT JOIN medewerkers ON medewerkers.id = koppeling.medewerker_id
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
			GROUP BY medewerkers.id";
		$hulpaanbiedingen = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker,
			'vrijwilligers met koppeling' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
			AND koppeling.iz_koppeling_id IS NOT NULL
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
			LEFT JOIN medewerkers ON medewerkers.id = koppeling.medewerker_id
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
			GROUP BY medewerkers.id";
		$koppelingen = $this->query($sql);

		$result = array_merge($aanmeldingen, $intakes, $hulpaanbiedingen, $koppelingen);

		return $this->getPivotTableData($result, '0.fase', '0.medewerker', '0.aantal', false, true, false, true);
	}

	public function count_nieuw_gestart(DateTime $startDate, DateTime $endDate)
	{
		$sql = $this->count(
			$this->nieuw_gestart($startDate, $endDate, 'koppeling.startdatum', 'koppeling.einddatum')
		);
		$metHulp = $this->query($sql);

		$sql = $this->count(
			$this->nieuw_gestart($startDate, $endDate, 'koppeling.koppeling_startdatum', 'koppeling.koppeling_einddatum'),
			true
		);
		$metKoppeling = $this->query($sql);

		$result = array_merge($metHulp, $metKoppeling);

		return $this->getPivotTableData($result, '0.fase', '0.model', '0.aantal', false, false, false);
	}

	public function count_per_coordinator_nieuw_gestart(DateTime $startDate, DateTime $endDate)
	{
		$xPath = '0.fase';
		$yPath = '0.medewerker';
		$nPath = '0.aantal';

		$sql = $this->count_per_coordinator(
			$this->nieuw_gestart($startDate, $endDate, 'koppeling.startdatum', 'koppeling.einddatum')
		);
		$metHulpaanbod = $this->query($sql);

		$sql = $this->count_per_coordinator(
			$this->nieuw_gestart($startDate, $endDate, 'koppeling.koppeling_startdatum', 'koppeling.koppeling_einddatum'),
			true
		);
		$metKoppeling = $this->query($sql);

		$result = array_merge($metHulpaanbod, $metKoppeling);

		return $this->getPivotTableData($result, $xPath, $yPath, $nPath, false, true, false);
	}

	public function count_per_project_stadsdeel_nieuw_gestart(DateTime $startDate, DateTime $endDate)
	{
		$sql = $this->count_per_project_stadsdeel(
			$this->nieuw_gestart($startDate, $endDate, 'koppeling.koppeling_startdatum', 'koppeling.koppeling_startdatum')
		);
		$result = $this->query($sql);

		return $this->getPivotTableData($result, 's.stadsdeel', 'p.naam', '0.aantal');
	}

	protected function count($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT v.id) AS aantal, 'vrijwilligers' AS model, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
			FROM iz_koppelingen koppeling
			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id  AND d.model = 'Vrijwilliger'
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key
			{$condition}";
	}

	protected function count_per_coordinator($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT v.id) AS aantal,
			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
			FROM iz_koppelingen koppeling
			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
			LEFT JOIN medewerkers ON medewerkers.id = koppeling.medewerker_id
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id AND d.model = 'Vrijwilliger'
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key
			{$condition}
			GROUP BY medewerkers.id";
	}

	protected function count_per_project($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT v.id) AS aantal, p.naam AS project, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
			FROM iz_koppelingen koppeling
			INNER JOIN iz_projecten p ON p.id = koppeling.project_id
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id AND d.model = 'Vrijwilliger'
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key
			{$condition}
			GROUP BY p.naam";
	}

	protected function count_per_stadsdeel($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT v.id) AS aantal, s.stadsdeel, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
			FROM iz_koppelingen koppeling
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id AND d.model = 'Vrijwilliger'
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key
			LEFT JOIN stadsdelen s ON s.postcode = v.postcode
			{$condition}
			GROUP BY s.stadsdeel";
	}

	protected function count_per_postcodegebied($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT v.id) AS aantal, pc.postcodegebied, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
			FROM iz_koppelingen koppeling
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id AND d.model = 'Vrijwilliger'
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key
			LEFT JOIN postcodegebieden pc ON SUBSTRING(v.postcode, 1, 4) BETWEEN pc.van AND pc.tot
			{$condition}
			GROUP BY pc.postcodegebied";
	}

	protected function count_per_project_stadsdeel($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT v.id) AS aantal, p.naam AS project, s.stadsdeel, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
			FROM iz_koppelingen koppeling
			INNER JOIN iz_projecten p ON p.id = koppeling.project_id
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id AND d.model = 'Vrijwilliger'
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key
			LEFT JOIN stadsdelen s ON s.postcode = v.postcode
			{$condition}
			GROUP BY p.naam, s.stadsdeel";
	}

	protected function count_per_project_postcodegebied($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT v.id) AS aantal, p.naam AS project, pc.postcodegebied, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
			FROM iz_koppelingen koppeling
			INNER JOIN iz_projecten p ON p.id = koppeling.project_id
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id AND d.model = 'Vrijwilliger'
			INNER JOIN vrijwilligers v ON v.id = d.foreign_key
			LEFT JOIN postcodegebieden pc ON SUBSTRING(v.postcode, 1, 4) BETWEEN pc.van AND pc.tot
			{$condition}
			GROUP BY p.naam, pc.postcodegebied";
	}

	protected function nieuw_gestart(DateTime $startDate, DateTime $endDate, $startDateField, $endDateField)
	{
		return "LEFT JOIN
			(
				SELECT d.id AS iz_deelnemer_id, k.id AS iz_koppeling_id, k.startdatum, p.naam AS project
				FROM iz_koppelingen k
				INNER JOIN iz_projecten p ON p.id = k.project_id
				INNER JOIN iz_deelnemers d ON d.id = k.iz_deelnemer_id
				WHERE k.koppeling_startdatum < '{$startDate->format('Y-m-d')}'
				GROUP BY iz_deelnemer_id
				HAVING k.startdatum = MAX(k.startdatum)
			) AS vorige_koppeling ON vorige_koppeling.iz_deelnemer_id = koppeling.iz_deelnemer_id
			WHERE (vorige_koppeling.project IS NULL OR vorige_koppeling.project <> p.naam)
			AND $startDateField >= '{$startDate->format('Y-m-d')}'
			AND $startDateField <= '{$endDate->format('Y-m-d')}'";
	}

	public function count_intakes_per_coordinator_beginstand(DateTime $startDate)
	{
		$sql = $this->count_intakes_per_coordinator(
			$this->beginstand($startDate)
		);
		$result = $this->query($sql);

		return $this->getPivotTableData($result, null, '0.medewerker', '0.aantal');
	}

	public function count_intakes_per_coordinator_gestart(DateTime $startDate, DateTime $endDate)
	{
		$sql = $this->count_intakes_per_coordinator(
			$this->gestart($startDate, $endDate)
		);
		$result = $this->query($sql);

		return $this->getPivotTableData($result, null, '0.medewerker', '0.aantal');
	}

	public function count_intakes_per_coordinator_afgesloten(DateTime $startDate, DateTime $endDate)
	{
		$sql = $this->count_intakes_per_coordinator(
			$this->afgesloten($startDate, $endDate)
		);
		$result = $this->query($sql);

		return $this->getPivotTableData($result, null, '0.medewerker', '0.aantal');
	}

	public function count_intakes_per_coordinator_eindstand(DateTime $endDate)
	{
		$sql = $this->count_intakes_per_coordinator(
			$this->eindstand($endDate)
		);
		$result = $this->query($sql);

		return $this->getPivotTableData($result, null, '0.medewerker', '0.aantal');
	}
}
