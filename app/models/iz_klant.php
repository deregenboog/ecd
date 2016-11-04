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

    public function getFilteredIds(array $filterData)
    {
        list($conditions, $joins) = $this->getConditionsAndJoins(
            $filterData['Query']['name'],
            new DateTime($filterData['Query']['from']),
            new DateTime($filterData['Query']['until'])
        );

        if (key_exists('Klant', $filterData)) {
            if (isset($filterData['Klant']['medewerker_id'])) {
                $conditions[] = ['Klant.medewerker_id' => $filterData['Klant']['medewerker_id']];
            }
        }

        if (key_exists('IzIntake', $filterData)) {
            $izIntakeConditions = ['IzIntake.iz_deelnemer_id = IzKlant.id'];
            if (isset($filterData['IzIntake']['medewerker_id'])) {
                $izIntakeConditions[] = ['IzIntake.medewerker_id' => $filterData['IzIntake']['medewerker_id']];
            }
            $joins[] = array(
                'table' => 'iz_intakes',
                'alias' => 'IzIntake',
                'type' => 'INNER',
                'conditions' => $izIntakeConditions,
            );
        }

        if (key_exists('IzHulpvraag', $filterData)) {
            if (isset($filterData['IzHulpvraag']['medewerker_id'])) {
                $conditions[] = ['IzHulpvraag.medewerker_id' => $filterData['IzHulpvraag']['medewerker_id']];
            }

            if (isset($filterData['IzHulpvraag']['project_id'])) {
                $conditions[] = ['IzHulpvraag.project_id' => $filterData['IzHulpvraag']['project_id']];
            }
        }

        if (key_exists('Stadsdeel', $filterData)) {
            if (isset($filterData['Stadsdeel']['stadsdeel'])) {
                $conditions[] = ['Stadsdeel.stadsdeel' => $filterData['Stadsdeel']['stadsdeel']];
            }
            $joins[] = array(
                'table' => 'stadsdelen',
                'alias' => 'Stadsdeel',
                'type' => 'LEFT',
                'conditions' => ['Stadsdeel.postcode = Klant.postcode'],
            );
        }

        if (key_exists('Postcodegebied', $filterData)) {
            if (isset($filterData['Postcodegebied']['postcodegebied'])) {
                $conditions[] = ['Postcodegebied.postcodegebied' => $filterData['Postcodegebied']['postcodegebied']];
            }
            $joins[] = array(
                'table' => 'postcodegebieden',
                'alias' => 'Postcodegebied',
                'type' => 'LEFT',
                'conditions' => ['SUBSTRING(Klant.postcode, 1, 4) BETWEEN Postcodegebied.van AND Postcodegebied.tot'],
            );
        }

        $list = $this->find('list', [
            'joins' => $joins,
            'conditions' => $conditions,
            'recursive' => -1,
        ]);

        return $list;
    }

    protected function getConditionsAndJoins($report, DateTime $startDate, DateTime $endDate, $groupByField = null)
    {
        $conditions = ['IzKlant.model' => 'Klant'];
        $joins = [];

        $klantConditions = ['Klant.id = IzKlant.foreign_key'];
        $joins[] = array(
            'table' => 'klanten',
            'alias' => 'Klant',
            'type' => 'INNER',
            'conditions' => $klantConditions,
        );

        $izHulpvraagConditions = ['IzHulpvraag.iz_deelnemer_id = IzKlant.id'];

        switch ($report) {
            case 'beginstand':
                $izHulpvraagConditions['IzHulpvraag.koppeling_startdatum <'] = $startDate->format('Y-m-d');
                $izHulpvraagConditions['OR'] = [
                    'IzHulpvraag.koppeling_einddatum IS NULL',
                    'IzHulpvraag.koppeling_einddatum >=' => $startDate->format('Y-m-d'),
                ];
                break;
            case 'gestart':
                $izHulpvraagConditions[] = ['IzHulpvraag.koppeling_startdatum BETWEEN ? AND ?' => [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d'),
                ]];
                $joins[] = array(
                    'table' => 'iz_koppelingen',
                    'alias' => 'BeginstandIzHulpvraag',
                    'type' => 'LEFT',
                    'conditions' => [
                        'BeginstandIzHulpvraag.iz_deelnemer_id = IzHulpvraag.iz_deelnemer_id',
                        $groupByField && preg_match('/^IzHulpvraag\./', $groupByField)
                            ? "Beginstand{$groupByField} = {$groupByField}"
                            : null,
                        'BeginstandIzHulpvraag.koppeling_startdatum <' => $startDate->format('Y-m-d'),
                        'OR' => [
                            'BeginstandIzHulpvraag.koppeling_einddatum IS NULL',
                            'BeginstandIzHulpvraag.koppeling_einddatum >=' => $startDate->format('Y-m-d'),
                        ],
                    ],
                );
                $conditions[] = ['BeginstandIzHulpvraag.id IS NULL'];
                break;
            case 'afgesloten':
            case 'succesvol_afgesloten':
                $izHulpvraagConditions[] = ['IzHulpvraag.koppeling_einddatum BETWEEN ? AND ?' => [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d'),
                ]];
                $joins[] = array(
                    'table' => 'iz_koppelingen',
                    'alias' => 'EindstandIzHulpvraag',
                    'type' => 'LEFT',
                    'conditions' => [
                        'EindstandIzHulpvraag.iz_deelnemer_id = IzHulpvraag.iz_deelnemer_id',
                        $groupByField && preg_match('/^IzHulpvraag\./', $groupByField)
                            ? "Eindstand{$groupByField} = {$groupByField}"
                            : null,
                        'EindstandIzHulpvraag.koppeling_startdatum <=' => $endDate->format('Y-m-d'),
                        'OR' => [
                            'EindstandIzHulpvraag.koppeling_einddatum IS NULL',
                            'EindstandIzHulpvraag.koppeling_einddatum >' => $endDate->format('Y-m-d'),
                        ],
                    ],
                );
                $conditions[] = 'EindstandIzHulpvraag.id IS NULL';
                if ($report === 'succesvol_afgesloten') {
                    $conditions[] = ['IzHulpvraag.koppeling_succesvol' => 1];
                }
                break;
            case 'eindstand':
                $izHulpvraagConditions['IzHulpvraag.koppeling_startdatum <='] = $endDate->format('Y-m-d');
                $izHulpvraagConditions['OR'] = [
                    'IzHulpvraag.koppeling_einddatum IS NULL',
                    'IzHulpvraag.koppeling_einddatum >' => $endDate->format('Y-m-d'),
                ];
                break;
        }

        array_unshift($joins, [
            'table' => 'iz_koppelingen',
            'alias' => 'IzHulpvraag',
            'type' => 'INNER',
            'conditions' => $izHulpvraagConditions,
        ]);

        return [$conditions, $joins];
    }

    public function count($report, $startDate, $endDate)
    {
        list($conditions, $joins) = $this->getConditionsAndJoins($report, $startDate, $endDate);

        $result = $this->find('all', array(
            'fields' => ['COUNT(DISTINCT Klant.id) AS aantal'],
            'joins' => $joins,
            'conditions' => $conditions,
            'recursive' => -1,
        ));

        $table = new Table($result, null, null, '0.aantal', $report);
        $table
            ->setController('iz_klanten')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setXTotals(true)
            ->setYTotals(true)
            ->setXSort(false)
            ->setYSort(false);

        return $table->render();
    }

    public function count_per_coordinator($report, $startDate, $endDate)
    {
        $groupByField = 'IzHulpvraag.medewerker_id';
        list($conditions, $joins) = $this->getConditionsAndJoins($report, $startDate, $endDate, $groupByField);

        $result = $this->find('all', array(
            'fields' => [
                $groupByField,
                'COUNT(DISTINCT Klant.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => [$groupByField],
            'recursive' => -1,
        ));

        $table = new Table($result, null, $groupByField, '0.aantal', $report);
        $table
            ->setController('iz_klanten')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setXTotals(true)
            ->setYTotals(false)
            ->setXSort(false)
            ->setYSort(false);

        return $table->render();
    }

    public function count_per_project($report, $startDate, $endDate)
    {
        $groupByField = 'IzHulpvraag.project_id';
        list($conditions, $joins) = $this->getConditionsAndJoins($report, $startDate, $endDate, $groupByField);

        $result = $this->find('all', array(
            'fields' => [
                $groupByField,
                'COUNT(DISTINCT Klant.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => [$groupByField],
            'recursive' => -1,
        ));

        $table = new Table($result, null, $groupByField, '0.aantal', $report);
        $table
            ->setController('iz_klanten')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setYTotals(false);

        return $table->render();
    }

    public function count_per_stadsdeel($report, $startDate, $endDate)
    {
        $groupByField = 'Stadsdeel.stadsdeel';
        list($conditions, $joins) = $this->getConditionsAndJoins($report, $startDate, $endDate, $groupByField);

        $joins[] = array(
            'table' => 'stadsdelen',
            'alias' => 'Stadsdeel',
            'type' => 'LEFT',
            'conditions' => ['Stadsdeel.postcode = Klant.postcode'],
        );

        $result = $this->find('all', array(
            'fields' => [
                'Stadsdeel.stadsdeel',
                'COUNT(DISTINCT Klant.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => [$groupByField],
            'recursive' => -1,
        ));

        $table = new Table($result, null, $groupByField, '0.aantal', $report);
        $table
            ->setController('iz_klanten')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setYNullReplacement('Overig')
        ;

        return $table->render();
    }

    public function count_per_postcodegebied($report, $startDate, $endDate)
    {
        $groupByField = 'Postcodegebied.postcodegebied';
        list($conditions, $joins) = $this->getConditionsAndJoins($report, $startDate, $endDate, $groupByField);

        $joins[] = array(
            'table' => 'postcodegebieden',
            'alias' => 'Postcodegebied',
            'type' => 'LEFT',
            'conditions' => ['SUBSTRING(Klant.postcode, 1, 4) BETWEEN Postcodegebied.van AND Postcodegebied.tot'],
        );

        $result = $this->find('all', array(
            'fields' => [
                $groupByField,
                'COUNT(DISTINCT Klant.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => [$groupByField],
            'recursive' => -1,
        ));

        $table = new Table($result, null, $groupByField, '0.aantal', $report);
        $table
            ->setController('iz_klanten')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setYTotals(false)
        ;

        return $table->render();
    }

    public function count_per_project_stadsdeel($report, $startDate, $endDate)
    {
        list($conditions, $joins) = $this->getConditionsAndJoins($report, $startDate, $endDate);

        $joins[] = array(
            'table' => 'stadsdelen',
            'alias' => 'Stadsdeel',
            'type' => 'LEFT',
            'conditions' => ['Stadsdeel.postcode = Klant.postcode'],
        );

        $result = $this->find('all', array(
            'fields' => [
                'IzHulpvraag.project_id',
                'Stadsdeel.stadsdeel',
                'COUNT(DISTINCT Klant.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => ['IzHulpvraag.project_id', 'Stadsdeel.stadsdeel'],
            'recursive' => -1,
        ));

        $table = new Table($result, 'Stadsdeel.stadsdeel', 'IzHulpvraag.project_id', '0.aantal', $report);
        $table
            ->setController('iz_klanten')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setYTotals(false)
        ;

        return $table->render();
    }

    public function count_per_project_postcodegebied($report, $startDate, $endDate)
    {
        list($conditions, $joins) = $this->getConditionsAndJoins($report, $startDate, $endDate);

        $joins[] = array(
            'table' => 'postcodegebieden',
            'alias' => 'Postcodegebied',
            'type' => 'LEFT',
            'conditions' => ['SUBSTRING(Klant.postcode, 1, 4) BETWEEN Postcodegebied.van AND Postcodegebied.tot'],
        );

        $result = $this->find('all', array(
            'fields' => [
                'IzHulpvraag.project_id',
                'Postcodegebied.postcodegebied',
                'COUNT(DISTINCT Klant.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => ['IzHulpvraag.project_id', 'Postcodegebied.postcodegebied'],
            'recursive' => -1,
        ));

        $table = new Table($result, 'Postcodegebied.postcodegebied', 'IzHulpvraag.project_id', '0.aantal', $report);
        $table
            ->setController('iz_klanten')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setYTotals(false)
            ->setYSort(true)
        ;

        return $table->render();
    }

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

        $table = new Table($result, '0.fase', null, '0.aantal');
        $table
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setXTotals(false)
            ->setXSort(false);

        return $table->render();
    }

    public function count_aanmeldingen_per_coordinator(DateTime $startDate, DateTime $endDate)
    {
        $sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
      k.medewerker_id,
      'klanten met aanmelding' AS fase
      FROM iz_deelnemers d
      INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
      WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
      GROUP BY medewerker_id";
        $aanmeldingen = $this->query($sql);

        $sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
      k.medewerker_id,
      'klanten met intake' AS fase
      FROM iz_deelnemers d
      INNER JOIN iz_intakes i ON i.iz_deelnemer_id = d.id
      INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
      WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
      GROUP BY medewerker_id";
        $intakes = $this->query($sql);

        $sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
      k.medewerker_id,
      'klanten met hulpvraag' AS fase
      FROM iz_deelnemers d
      INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
      INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
      WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
      GROUP BY medewerker_id";
        $hulpaanbiedingen = $this->query($sql);

        $sql = "SELECT COUNT(DISTINCT k.id) AS aantal,
      k.medewerker_id,
      'klanten met koppeling' AS fase
      FROM iz_deelnemers d
      INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
      AND koppeling.iz_koppeling_id IS NOT NULL
      INNER JOIN klanten k ON k.id = d.foreign_key AND d.model = 'Klant'
      WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
      GROUP BY medewerker_id";
        $koppelingen = $this->query($sql);

        $result = array_merge($aanmeldingen, $intakes, $hulpaanbiedingen, $koppelingen);
        foreach ($result as $i => $row) {
            $result[$i][0]['medewerker_id'] = end($row)['medewerker_id'];
        }

        $table = new Table($result, '0.fase', '0.medewerker_id', '0.aantal');
        $table
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setXTotals(false)
            ->setXSort(false);

        return $table->render();
    }

//     protected function count($condition, $metKoppeling = false)
//     {
//         return "SELECT COUNT(k.id) AS aantal, 'Klanten' AS model, "
//             .($metKoppeling ? "'met koppeling'" : "'met hulpvraag'")." AS fase
// 			FROM klanten AS k
// 			INNER JOIN iz_deelnemers AS d ON k.id = d.foreign_key AND d.model = 'Klant'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			{$condition}";
//     }

//     protected function count_per_coordinator($condition, $metKoppeling = false)
//     {
//         return "SELECT COUNT(k.id) AS aantal,
// 			CONCAT_WS(' ', medewerkers.voornaam, medewerkers.achternaam) AS medewerker, "
//             .($metKoppeling ? "'met koppeling'" : "'met hulpvraag'")." AS fase
// 			FROM klanten AS k
// 			INNER JOIN iz_deelnemers AS d ON k.id = d.foreign_key AND d.model = 'Klant'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN medewerkers ON medewerkers.id = koppeling.medewerker_id
// 			{$condition}
// 			GROUP BY medewerkers.id";
//     }

//     protected function count_per_project($condition, $metKoppeling = false)
//     {
//         return 'SELECT COUNT(k.id) AS aantal, p.naam AS project, '
//             .($metKoppeling ? "'met koppeling'" : "'met hulpvraag'")." AS fase
// 			FROM klanten AS k
// 			INNER JOIN iz_deelnemers AS d ON k.id = d.foreign_key AND d.model = 'Klant'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
// 			{$condition}
// 			GROUP BY p.naam";
//     }

//     protected function count_per_stadsdeel($condition, $metKoppeling = false)
//     {
//         return 'SELECT COUNT(k.id) AS aantal, s.stadsdeel, '
//             .($metKoppeling ? "'met koppeling'" : "'met hulpvraag'")." AS fase
// 			FROM klanten AS k
// 			INNER JOIN iz_deelnemers AS d ON k.id = d.foreign_key AND d.model = 'Klant'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN stadsdelen s ON s.postcode = k.postcode
// 			{$condition}
// 			GROUP BY s.stadsdeel";
//     }

//     protected function count_per_postcodegebied($condition, $metKoppeling = false)
//     {
//         return 'SELECT COUNT(k.id) AS aantal, pc.postcodegebied, '
//             .($metKoppeling ? "'met koppeling'" : "'met hulpvraag'")." AS fase
// 			FROM klanten AS k
// 			INNER JOIN iz_deelnemers AS d ON k.id = d.foreign_key AND d.model = 'Klant'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN postcodegebieden pc ON SUBSTRING(k.postcode, 1, 4) BETWEEN pc.van AND pc.tot
// 			{$condition}
// 			GROUP BY pc.postcodegebied";
//     }

//     protected function count_per_project_stadsdeel($condition, $metKoppeling = false)
//     {
//         return 'SELECT COUNT(k.id) AS aantal, p.naam AS project, s.stadsdeel, '
//             .($metKoppeling ? "'met koppeling'" : "'met hulpvraag'")." AS fase
// 			FROM klanten AS k
// 			INNER JOIN iz_deelnemers AS d ON k.id = d.foreign_key AND d.model = 'Klant'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
// 			LEFT JOIN stadsdelen s ON s.postcode = k.postcode
// 			{$condition}
// 			GROUP BY p.naam, s.stadsdeel";
//     }

//     protected function count_per_project_postcodegebied($condition, $metKoppeling = false)
//     {
//         return 'SELECT COUNT(k.id) AS aantal, p.naam AS project, pc.postcodegebied, '
//             .($metKoppeling ? "'met koppeling'" : "'met hulpvraag'")." AS fase
// 			FROM klanten AS k
// 			INNER JOIN iz_deelnemers AS d ON k.id = d.foreign_key AND d.model = 'Klant'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
// 			LEFT JOIN postcodegebieden pc ON SUBSTRING(k.postcode, 1, 4) BETWEEN pc.van AND pc.tot
// 			{$condition}
// 			GROUP BY p.naam, pc.postcodegebied";
//     }
}
