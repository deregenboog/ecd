<?php

App::import('Model', 'IzDeelnemer');

class IzKlant extends IzDeelnemer
{
	public $name = 'IzKlant';

	public $belongsTo = array(
		'Klant' => array(
			'className' => 'Klant',
			'model' => 'Klant',
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
		'IzHulpvraag' => array(
			'className' => 'IzHulpvraag',
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
		$sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
			'klanten met aanmelding' AS fase
			FROM iz_deelnemers d
			INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'";
		$aanmeldingen = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
			'klanten met intake' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_intakes i ON i.iz_deelnemer_id = d.id
			INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'";
		$intakes = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
			'klanten met hulpvraag' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
			INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'";
		$hulpaanbiedingen = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
			'klanten met koppeling' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
			AND koppeling.iz_koppeling_id IS NOT NULL
			INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'";
		$koppelingen = $this->query($sql);

		$result = array_merge($aanmeldingen, $intakes, $hulpaanbiedingen, $koppelingen);

		return $this->getPivotTableData($result, '0.fase', null, '0.aantal', false, true, false, true);
	}

	public function count_aanmeldingen_per_coordinator(DateTime $startDate, DateTime $endDate)
	{
		$sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker,
			'klanten met aanmelding' AS fase
			FROM iz_deelnemers d
			INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
			LEFT JOIN medewerkers ON medewerkers.id = k.medewerker_id
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
			GROUP BY medewerkers.id";
		$aanmeldingen = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker,
			'klanten met intake' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_intakes i ON i.iz_deelnemer_id = d.id
			INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
			LEFT JOIN medewerkers ON medewerkers.id = i.medewerker_id
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
			GROUP BY medewerkers.id";
		$intakes = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker,
			'klanten met hulpvraag' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
			INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
			LEFT JOIN medewerkers ON medewerkers.id = koppeling.medewerker_id
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
			GROUP BY medewerkers.id";
		$hulpaanbiedingen = $this->query($sql);

		$sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker,
			'klanten met koppeling' AS fase
			FROM iz_deelnemers d
			INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
			AND koppeling.iz_koppeling_id IS NOT NULL
			INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
			LEFT JOIN medewerkers ON medewerkers.id = koppeling.medewerker_id
			WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
			GROUP BY medewerkers.id";
		$koppelingen = $this->query($sql);

		$result = array_merge($aanmeldingen, $intakes, $hulpaanbiedingen, $koppelingen);

		return $this->getPivotTableData($result, '0.fase', '0.medewerker', '0.aantal', false, true, false, true);
	}

	protected function count($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT k.id) AS aantal,
			'klanten' AS model, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpvraag'") . " AS fase
			FROM iz_koppelingen koppeling
			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
			INNER JOIN iz_deelnemers izklant ON izklant.id = koppeling.iz_deelnemer_id  AND izklant.model = 'Klant'
			INNER JOIN klanten k ON izklant.foreign_key = k.id
			{$condition}";
	}

	protected function count_per_coordinator($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT k.id) AS aantal,
			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpvraag'") . " AS fase
			FROM iz_koppelingen koppeling
			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
			LEFT JOIN medewerkers ON medewerkers.id = koppeling.medewerker_id
			INNER JOIN iz_deelnemers izklant ON izklant.id = koppeling.iz_deelnemer_id AND izklant.model = 'Klant'
			INNER JOIN klanten k ON k.id = izklant.foreign_key
			{$condition}
			GROUP BY medewerkers.id";
	}

	protected function count_per_project($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT k.id) AS aantal, p.naam AS project, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpvraag'") . " AS fase
			FROM iz_koppelingen koppeling
			INNER JOIN iz_projecten p ON p.id = koppeling.project_id
			INNER JOIN iz_deelnemers izklant ON izklant.id = koppeling.iz_deelnemer_id AND izklant.model = 'Klant'
			INNER JOIN klanten k ON izklant.foreign_key = k.id
			{$condition}
			GROUP BY p.naam";
	}

	protected function count_per_stadsdeel($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT k.id) AS aantal, s.stadsdeel, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpvraag'") . " AS fase
			FROM iz_koppelingen koppeling
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id AND d.model = 'Klant'
			INNER JOIN klanten k ON d.foreign_key = k.id
			LEFT JOIN stadsdelen s ON s.postcode = k.postcode
			{$condition}
			GROUP BY s.stadsdeel";
	}

	protected function count_per_postcodegebied($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT k.id) AS aantal, pc.postcodegebied, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpvraag'") . " AS fase
			FROM iz_koppelingen koppeling
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id AND d.model = 'Klant'
			INNER JOIN klanten k ON d.foreign_key = k.id
			LEFT JOIN postcodegebieden pc ON SUBSTRING(k.postcode, 1, 4) BETWEEN pc.van AND pc.tot
			{$condition}
			GROUP BY pc.postcodegebied";
	}

	protected function count_per_project_stadsdeel($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT k.id) AS aantal, p.naam AS project, s.stadsdeel, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpvraag'") . " AS fase
			FROM iz_koppelingen koppeling
			INNER JOIN iz_projecten p ON p.id = koppeling.project_id
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id AND d.model = 'Klant'
			INNER JOIN klanten k ON d.foreign_key = k.id
			LEFT JOIN stadsdelen s ON s.postcode = k.postcode
			{$condition}
			GROUP BY p.naam, s.stadsdeel";
	}

	protected function count_per_project_postcodegebied($condition, $metKoppeling = false)
	{
		return "SELECT COUNT(DISTINCT k.id) AS aantal, p.naam AS project, pc.postcodegebied, "
			. ($metKoppeling ? "'met koppeling'" : "'met hulpvraag'") . " AS fase
			FROM iz_koppelingen koppeling
			INNER JOIN iz_projecten p ON p.id = koppeling.project_id
			INNER JOIN iz_deelnemers d ON d.id = koppeling.iz_deelnemer_id AND d.model = 'Klant'
			INNER JOIN klanten k ON d.foreign_key = k.id
			LEFT JOIN postcodegebieden pc ON SUBSTRING(k.postcode, 1, 4) BETWEEN pc.van AND pc.tot
			{$condition}
			GROUP BY p.naam, pc.postcodegebied";
	}
}
