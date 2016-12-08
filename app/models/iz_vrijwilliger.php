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

    public function getFilteredIds(array $filterData)
    {
        list($conditions, $joins) = $this->getConditionsAndJoins(
            $filterData['Query']['name'],
            new DateTime($filterData['Query']['from']),
            new DateTime($filterData['Query']['until'])
        );

        if (key_exists('Vrijwilliger', $filterData)) {
            if (isset($filterData['Vrijwilliger']['medewerker_id'])) {
                $conditions[] = ['Vrijwilliger.medewerker_id' => $filterData['Vrijwilliger']['medewerker_id']];
            }
        }

        if (key_exists('IzIntake', $filterData)) {
            $izIntakeConditions = ['IzIntake.iz_deelnemer_id = IzVrijwilliger.id'];
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

        if (key_exists('IzHulpaanbod', $filterData)) {
            if (isset($filterData['IzHulpaanbod']['medewerker_id'])) {
                $conditions[] = ['IzHulpaanbod.medewerker_id' => $filterData['IzHulpaanbod']['medewerker_id']];
            }

            if (isset($filterData['IzHulpaanbod']['project_id'])) {
                $conditions[] = ['IzHulpaanbod.project_id' => $filterData['IzHulpaanbod']['project_id']];
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
                'conditions' => ['Stadsdeel.postcode = Vrijwilliger.postcode'],
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
                'conditions' => ['SUBSTRING(Vrijwilliger.postcode, 1, 4) BETWEEN Postcodegebied.van AND Postcodegebied.tot'],
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
        $conditions = ['IzVrijwilliger.model' => 'Vrijwilliger'];
        $joins = [];

        $vrijwilligerConditions = ['Vrijwilliger.id = IzVrijwilliger.foreign_key'];
        $joins[] = array(
            'table' => 'vrijwilligers',
            'alias' => 'Vrijwilliger',
            'type' => 'INNER',
            'conditions' => $vrijwilligerConditions,
        );

        $izHulpaanbodConditions = ['IzHulpaanbod.iz_deelnemer_id = IzVrijwilliger.id'];

        switch ($report) {
            case 'beginstand':
                $izHulpaanbodConditions['IzHulpaanbod.koppeling_startdatum <'] = $startDate->format('Y-m-d');
                $izHulpaanbodConditions['OR'] = [
                    'IzHulpaanbod.koppeling_einddatum IS NULL',
                    'IzHulpaanbod.koppeling_einddatum >=' => $startDate->format('Y-m-d'),
                ];
                break;
            case 'gestart':
                $izHulpaanbodConditions[] = ['IzHulpaanbod.koppeling_startdatum BETWEEN ? AND ?' => [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d'),
                ]];
                $joins[] = array(
                    'table' => 'iz_koppelingen',
                    'alias' => 'BeginstandIzHulpaanbod',
                    'type' => 'LEFT',
                    'conditions' => [
                        'BeginstandIzHulpaanbod.iz_deelnemer_id = IzHulpaanbod.iz_deelnemer_id',
                        $groupByField && preg_match('/^IzHulpaanbod\./', $groupByField)
                            ? "Beginstand{$groupByField} = {$groupByField}"
                            : null,
                        'BeginstandIzHulpaanbod.koppeling_startdatum <' => $startDate->format('Y-m-d'),
                        'OR' => [
                            'BeginstandIzHulpaanbod.koppeling_einddatum IS NULL',
                            'BeginstandIzHulpaanbod.koppeling_einddatum >=' => $startDate->format('Y-m-d'),
                        ],
                    ],
                );
                $conditions[] = ['BeginstandIzHulpaanbod.id IS NULL'];
                break;
            case 'afgesloten':
            case 'succesvol_afgesloten':
                $izHulpaanbodConditions[] = ['IzHulpaanbod.koppeling_einddatum BETWEEN ? AND ?' => [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d'),
                ]];
                $joins[] = array(
                    'table' => 'iz_koppelingen',
                    'alias' => 'EindstandIzHulpaanbod',
                    'type' => 'LEFT',
                    'conditions' => [
                        'EindstandIzHulpaanbod.iz_deelnemer_id = IzHulpaanbod.iz_deelnemer_id',
                        $groupByField && preg_match('/^IzHulpaanbod\./', $groupByField)
                            ? "Eindstand{$groupByField} = {$groupByField}"
                            : null,
                        'EindstandIzHulpaanbod.koppeling_startdatum <=' => $endDate->format('Y-m-d'),
                        'OR' => [
                            'EindstandIzHulpaanbod.koppeling_einddatum IS NULL',
                            'EindstandIzHulpaanbod.koppeling_einddatum >' => $endDate->format('Y-m-d'),
                        ],
                    ],
                );
                $conditions[] = 'EindstandIzHulpaanbod.id IS NULL';
                if ($report === 'succesvol_afgesloten') {
                    $conditions[] = ['IzHulpaanbod.koppeling_succesvol' => 1];
                }
                break;
            case 'eindstand':
                $izHulpaanbodConditions['IzHulpaanbod.koppeling_startdatum <='] = $endDate->format('Y-m-d');
                $izHulpaanbodConditions['OR'] = [
                    'IzHulpaanbod.koppeling_einddatum IS NULL',
                    'IzHulpaanbod.koppeling_einddatum >' => $endDate->format('Y-m-d'),
                ];
                break;
        }

        array_unshift($joins, [
            'table' => 'iz_koppelingen',
            'alias' => 'IzHulpaanbod',
            'type' => 'INNER',
            'conditions' => $izHulpaanbodConditions,
        ]);

        return [$conditions, $joins];
    }

    public function count($report, $startDate, $endDate)
    {
        list($conditions, $joins) = $this->getConditionsAndJoins($report, $startDate, $endDate);

        $result = $this->find('all', array(
            'fields' => ['COUNT(DISTINCT Vrijwilliger.id) AS aantal'],
            'joins' => $joins,
            'conditions' => $conditions,
            'recursive' => -1,
        ));

        $table = new Table($result, null, null, '0.aantal', $report);
        $table
            ->setController('iz_vrijwilligers')
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
        $groupByField = 'IzHulpaanbod.medewerker_id';
        list($conditions, $joins) = $this->getConditionsAndJoins($report, $startDate, $endDate, $groupByField);

        $result = $this->find('all', array(
            'fields' => [
                $groupByField,
                'COUNT(DISTINCT Vrijwilliger.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => [$groupByField],
            'recursive' => -1,
        ));

        $table = new Table($result, null, $groupByField, '0.aantal', $report);
        $table
            ->setController('iz_vrijwilligers')
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
        $groupByField = 'IzHulpaanbod.project_id';
        list($conditions, $joins) = $this->getConditionsAndJoins($report, $startDate, $endDate, $groupByField);

        $result = $this->find('all', array(
            'fields' => [
                $groupByField,
                'COUNT(DISTINCT Vrijwilliger.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => [$groupByField],
            'recursive' => -1,
        ));

        $table = new Table($result, null, $groupByField, '0.aantal', $report);
        $table
            ->setController('iz_vrijwilligers')
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
            'conditions' => ['Stadsdeel.postcode = Vrijwilliger.postcode'],
        );

        $result = $this->find('all', array(
            'fields' => [
                'Stadsdeel.stadsdeel',
                'COUNT(DISTINCT Vrijwilliger.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => [$groupByField],
            'recursive' => -1,
        ));

        $table = new Table($result, null, $groupByField, '0.aantal', $report);
        $table
            ->setController('iz_vrijwilligers')
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
            'conditions' => ['SUBSTRING(Vrijwilliger.postcode, 1, 4) BETWEEN Postcodegebied.van AND Postcodegebied.tot'],
        );

        $result = $this->find('all', array(
            'fields' => [
                $groupByField,
                'COUNT(DISTINCT Vrijwilliger.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => [$groupByField],
            'recursive' => -1,
        ));

        $table = new Table($result, null, $groupByField, '0.aantal', $report);
        $table
            ->setController('iz_vrijwilligers')
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
            'conditions' => ['Stadsdeel.postcode = Vrijwilliger.postcode'],
        );

        $result = $this->find('all', array(
            'fields' => [
                'IzHulpaanbod.project_id',
                'Stadsdeel.stadsdeel',
                'COUNT(DISTINCT Vrijwilliger.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => ['IzHulpaanbod.project_id', 'Stadsdeel.stadsdeel'],
            'recursive' => -1,
        ));

        $table = new Table($result, 'Stadsdeel.stadsdeel', 'IzHulpaanbod.project_id', '0.aantal', $report);
        $table
            ->setController('iz_vrijwilligers')
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
            'conditions' => ['SUBSTRING(Vrijwilliger.postcode, 1, 4) BETWEEN Postcodegebied.van AND Postcodegebied.tot'],
        );

        $result = $this->find('all', array(
            'fields' => [
                'IzHulpaanbod.project_id',
                'Postcodegebied.postcodegebied',
                'COUNT(DISTINCT Vrijwilliger.id) AS aantal',
            ],
            'joins' => $joins,
            'conditions' => $conditions,
            'group' => ['IzHulpaanbod.project_id', 'Postcodegebied.postcodegebied'],
            'recursive' => -1,
        ));

        $table = new Table($result, 'Postcodegebied.postcodegebied', 'IzHulpaanbod.project_id', '0.aantal', $report);
        $table
            ->setController('iz_vrijwilligers')
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
        $sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
      v.medewerker_id,
      'vrijwilligers met aanmelding' AS fase
      FROM iz_deelnemers d
      INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
      WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
      GROUP BY medewerker_id";
        $aanmeldingen = $this->query($sql);

        $sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
      i.medewerker_id,
      'vrijwilligers met intake' AS fase
      FROM iz_deelnemers d
      INNER JOIN iz_intakes i ON i.iz_deelnemer_id = d.id
      INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
      WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
      GROUP BY medewerker_id";
        $intakes = $this->query($sql);

        $sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
      koppeling.medewerker_id,
      'vrijwilligers met hulpaanbod' AS fase
      FROM iz_deelnemers d
      INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
      INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
      WHERE d.datum_aanmelding BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
      GROUP BY medewerker_id";
        $hulpaanbiedingen = $this->query($sql);

        $sql = "SELECT COUNT(DISTINCT v.id) AS aantal,
      koppeling.medewerker_id,
      'vrijwilligers met koppeling' AS fase
      FROM iz_deelnemers d
      INNER JOIN iz_koppelingen koppeling ON koppeling.iz_deelnemer_id = d.id
      AND koppeling.iz_koppeling_id IS NOT NULL
      INNER JOIN vrijwilligers v ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
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

        return $this->getPivotTableData($result, '0.fase', 'koppeling.medewerker_id', '0.aantal', false, true, false);
    }

    public function count_per_project_nieuw_gestart(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project(
            $this->nieuw_gestart($startDate, $endDate, 'koppeling.startdatum', 'koppeling.einddatum')
        );
        $metHulpaanbod = $this->query($sql);

        $sql = $this->count_per_project(
            $this->nieuw_gestart($startDate, $endDate, 'koppeling.koppeling_startdatum', 'koppeling.koppeling_einddatum'),
            true
        );
        $metKoppeling = $this->query($sql);

        $result = array_merge($metHulpaanbod, $metKoppeling);

        return $this->getPivotTableData($result, '0.fase', 'p.project', '0.aantal', false, true, false);
    }

    public function count_per_stadsdeel_nieuw_gestart(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_stadsdeel(
            $this->nieuw_gestart($startDate, $endDate, 'koppeling.koppeling_startdatum', 'koppeling.koppeling_einddatum')
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
    }

    public function count_per_postcodegebied_nieuw_gestart(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_postcodegebied(
            $this->nieuw_gestart($startDate, $endDate, 'koppeling.koppeling_startdatum', 'koppeling.koppeling_einddatum')
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
    }

    public function count_per_project_stadsdeel_nieuw_gestart(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project_stadsdeel(
            $this->nieuw_gestart($startDate, $endDate, 'koppeling.koppeling_startdatum', 'koppeling.koppeling_einddatum')
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
    }

    public function count_per_project_postcodegebied_nieuw_gestart(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project_stadsdeel(
            $this->nieuw_gestart($startDate, $endDate, 'koppeling.koppeling_startdatum', 'koppeling.koppeling_einddatum')
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
    }

// 	protected function count($condition, $metKoppeling = false)
// 	{
// 		return "SELECT COUNT(DISTINCT v.id) AS aantal, 'Vrijwilligers' AS model, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN iz_koppelingen AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			{$condition}";

// 		return "SELECT COUNT(v.id) AS aantal, 'Vrijwilligers' AS model, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			{$condition}";
// 	}

    protected function select($columns, $condition, $metKoppeling = false)
    {
        return "SELECT DISTINCT $columns
      FROM vrijwilligers AS v
      INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
      INNER JOIN iz_koppelingen AS koppeling ON koppeling.iz_deelnemer_id = d.id
      {$condition}";
// 		return "SELECT DISTINCT v.id, koppeling.medewerker_id
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN iz_koppelingen AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			{$condition}";
    }

// 	protected function count_per_coordinator($condition, $metKoppeling = false)
// 	{
// 		return "SELECT COUNT(DISTINCT v.id) AS aantal,
// 			koppeling.medewerker_id, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN iz_koppelingen AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN medewerkers ON medewerkers.id = koppeling.medewerker_id
// 			{$condition}
// 			GROUP BY medewerkers.id";

// 		return "SELECT COUNT(v.id) AS aantal,
// 			koppeling.medewerker_id, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN medewerkers ON medewerkers.id = koppeling.medewerker_id
// 			{$condition}
// 			GROUP BY medewerkers.id";
// 	}

// 	protected function count_per_project($condition, $metKoppeling = false)
// 	{
// 		return "SELECT COUNT(DISTINCT v.id) AS aantal, p.naam AS project, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN iz_koppelingen AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
// 			{$condition}
// 			GROUP BY p.naam";

// 		return "SELECT COUNT(v.id) AS aantal, p.naam AS project, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
// 			{$condition}
// 			GROUP BY p.naam";
// 	}

// 	protected function count_per_stadsdeel($condition, $metKoppeling = false)
// 	{
// 		return "SELECT COUNT(DISTINCT v.id) AS aantal, s.stadsdeel, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN iz_koppelingen AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN stadsdelen s ON s.postcode = v.postcode
// 			{$condition}
// 			GROUP BY s.stadsdeel";

// 		return "SELECT COUNT(v.id) AS aantal, s.stadsdeel, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN stadsdelen s ON s.postcode = v.postcode
// 			{$condition}
// 			GROUP BY s.stadsdeel";
// 	}

// 	protected function count_per_postcodegebied($condition, $metKoppeling = false)
// 	{
// 		return "SELECT COUNT(DISTINCT v.id) AS aantal, pc.postcodegebied, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN iz_koppelingen AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN postcodegebieden pc ON SUBSTRING(v.postcode, 1, 4) BETWEEN pc.van AND pc.tot
// 			{$condition}
// 			GROUP BY pc.postcodegebied";

// 		return "SELECT COUNT(v.id) AS aantal, pc.postcodegebied, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN postcodegebieden pc ON SUBSTRING(v.postcode, 1, 4) BETWEEN pc.van AND pc.tot
// 			{$condition}
// 			GROUP BY pc.postcodegebied";
// 	}

// 	protected function count_per_project_stadsdeel($condition, $metKoppeling = false)
// 	{
// 		return "SELECT COUNT(DISTINCT v.id) AS aantal, p.naam AS project, s.stadsdeel, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN iz_koppelingen AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
// 			LEFT JOIN stadsdelen s ON s.postcode = v.postcode
// 			{$condition}
// 			GROUP BY p.naam, s.stadsdeel";

// 		return "SELECT COUNT(v.id) AS aantal, p.naam AS project, s.stadsdeel, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
// 			LEFT JOIN stadsdelen s ON s.postcode = v.postcode
// 			{$condition}
// 			GROUP BY p.naam, s.stadsdeel";
// 	}

// 	protected function count_per_project_postcodegebied($condition, $metKoppeling = false)
// 	{
// 		return "SELECT COUNT(DISTINCT v.id) AS aantal, p.naam AS project, pc.postcodegebied, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN iz_koppelingen AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
// 			LEFT JOIN postcodegebieden pc ON SUBSTRING(v.postcode, 1, 4) BETWEEN pc.van AND pc.tot
// 			{$condition}
// 			GROUP BY p.naam, pc.postcodegebied";

// 		return "SELECT COUNT(v.id) AS aantal, p.naam AS project, pc.postcodegebied, "
// 			. ($metKoppeling ? "'met koppeling'" : "'met hulpaanbod'") . " AS fase
// 			FROM vrijwilligers AS v
// 			INNER JOIN iz_deelnemers AS d ON v.id = d.foreign_key AND d.model = 'Vrijwilliger'
// 			INNER JOIN (
// 				SELECT * FROM iz_koppelingen
// 				GROUP BY iz_deelnemer_id
// 				HAVING startdatum = MAX(startdatum)
// 			) AS koppeling ON koppeling.iz_deelnemer_id = d.id
// 			LEFT JOIN iz_projecten p ON p.id = koppeling.project_id
// 			LEFT JOIN postcodegebieden pc ON SUBSTRING(v.postcode, 1, 4) BETWEEN pc.van AND pc.tot
// 			{$condition}
// 			GROUP BY p.naam, pc.postcodegebied";
// 	}

    protected function gestart(DateTime $startDate, DateTime $endDate, $startDateField, $endDateField)
    {
        return "WHERE $startDateField BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
      AND (v.id) NOT IN ({$this->select(
                'v.id',
                $this->beginstand($startDate, $startDateField, $endDateField)
            )})";
// 	    return "WHERE $startDateField BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
// 			AND (v.id, koppeling.medewerker_id) NOT IN ({$this->select(
// 				$this->beginstand($startDate, $startDateField, $endDateField)
// 			)})";
    }

// 	protected function afgesloten(DateTime $startDate, DateTime $endDate, $startDateField, $endDateField)
// 	{
// 		return "WHERE $endDateField BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'
// 			AND v.id NOT IN ({$this->select(
// 				$this->eindstand($endDate, $startDateField, $endDateField)
// 			)})";
// 	}

    protected function nieuw_gestart(DateTime $startDate, DateTime $endDate, $startDateField, $endDateField)
    {
        return "LEFT JOIN (
        SELECT koppeling.*
        FROM iz_koppelingen AS koppeling
        WHERE $startDateField < '{$startDate->format('Y-m-d')}'
        GROUP BY iz_deelnemer_id
        HAVING $startDateField = MAX($startDateField)
      ) AS vorige_koppeling ON vorige_koppeling.iz_deelnemer_id = d.id
      WHERE (vorige_koppeling.project_id IS NULL OR vorige_koppeling.project_id <> koppeling.project_id)
      AND $startDateField BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}'";
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
