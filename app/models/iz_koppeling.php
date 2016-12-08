<?php

class IzKoppeling extends AppModel
{
    // 	public $name = 'IzKoppeling';

    public $useTable = 'iz_koppelingen';

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

        $today = date('Y-m-d');

        $conditions = array(
            'IzDeelnemer.model' => $model,
            'IzKoppeling.iz_koppeling_id' => null,
            'IzKoppeling.project_id' => $project_ids,
            array(
               'OR' => array(
                    'IzKoppeling.einddatum' => null,
                    'IzKoppeling.einddatum >=' => $today,
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
            $projects[$a['IzKoppeling']['project_id']][$a['IzKoppeling']['id']] = $a[$model]['name'];
        }

        return $projects;
    }

    public function count_per_project_beginstand(DateTime $startDate)
    {
        $sql = $this->count_per_project(
            $this->beginstand($startDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 'p.project', '0.aantal');
    }

    public function count_per_project_gestart(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project(
            $this->gestart($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 'p.project', '0.aantal');
    }

    public function count_per_project_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project(
            $this->afgesloten($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 'p.project', '0.aantal');
    }

    public function count_per_project_succesvol_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project(
            $this->succesvol_afgesloten($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 'p.project', '0.aantal');
    }

    public function count_per_project_eindstand(DateTime $endDate)
    {
        $sql = $this->count_per_project(
            $this->eindstand($endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 'p.project', '0.aantal');
    }

    public function count_per_stadsdeel_beginstand(DateTime $startDate)
    {
        $sql = $this->count_per_stadsdeel(
            $this->beginstand($startDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 's.stadsdeel', '0.aantal');
    }

    public function count_per_stadsdeel_gestart(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_stadsdeel(
            $this->gestart($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 's.stadsdeel', '0.aantal');
    }

    public function count_per_stadsdeel_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_stadsdeel(
            $this->afgesloten($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 's.stadsdeel', '0.aantal');
    }

    public function count_per_stadsdeel_succesvol_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_stadsdeel(
            $this->succesvol_afgesloten($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 's.stadsdeel', '0.aantal');
    }

    public function count_per_stadsdeel_eindstand(DateTime $endDate)
    {
        $sql = $this->count_per_stadsdeel(
            $this->eindstand($endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 's.stadsdeel', '0.aantal');
    }

    public function count_per_postcodegebied_beginstand(DateTime $startDate)
    {
        $sql = $this->count_per_postcodegebied(
            $this->beginstand($startDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 'pc.postcodegebied', '0.aantal');
    }

    public function count_per_postcodegebied_gestart(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_postcodegebied(
            $this->gestart($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 'pc.postcodegebied', '0.aantal');
    }

    public function count_per_postcodegebied_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_postcodegebied(
            $this->afgesloten($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 'pc.postcodegebied', '0.aantal');
    }

    public function count_per_postcodegebied_succesvol_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_postcodegebied(
            $this->succesvol_afgesloten($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 'pc.postcodegebied', '0.aantal');
    }

    public function count_per_postcodegebied_eindstand(DateTime $endDate)
    {
        $sql = $this->count_per_postcodegebied(
            $this->eindstand($endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, null, 'pc.postcodegebied', '0.aantal');
    }

    public function count_per_project_stadsdeel_beginstand(DateTime $startDate)
    {
        $sql = $this->count_per_project_stadsdeel(
            $this->beginstand($startDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
    }

    public function count_per_project_stadsdeel_gestart(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project_stadsdeel(
            $this->gestart($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
    }

    public function count_per_project_stadsdeel_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project_stadsdeel(
            $this->afgesloten($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
    }

    public function count_per_project_stadsdeel_succesvol_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project_stadsdeel(
            $this->succesvol_afgesloten($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
    }

    public function count_per_project_stadsdeel_eindstand(DateTime $endDate)
    {
        $sql = $this->count_per_project_stadsdeel(
            $this->eindstand($endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 's.stadsdeel', 'p.project', '0.aantal');
    }

    public function count_per_project_postcodegebied_beginstand(DateTime $startDate)
    {
        $sql = $this->count_per_project_postcodegebied(
            $this->beginstand($startDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
    }

    public function count_per_project_postcodegebied_gestart(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project_postcodegebied(
            $this->gestart($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
    }

    public function count_per_project_postcodegebied_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project_postcodegebied(
            $this->afgesloten($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
    }

    public function count_per_project_postcodegebied_succesvol_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        $sql = $this->count_per_project_postcodegebied(
            $this->succesvol_afgesloten($startDate, $endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
    }

    public function count_per_project_postcodegebied_eindstand(DateTime $endDate)
    {
        $sql = $this->count_per_project_postcodegebied(
            $this->eindstand($endDate)
        );
        $result = $this->query($sql);

        return $this->getPivotTableData($result, 'pc.postcodegebied', 'p.project', '0.aantal');
    }

    private function count_per_project($condition)
    {
        return "SELECT COUNT(DISTINCT vraag.id) AS aantal, p.naam AS project
            FROM iz_koppelingen vraag
            INNER JOIN iz_projecten p ON p.id = vraag.project_id
            INNER JOIN iz_deelnemers izklant ON izklant.id = vraag.iz_deelnemer_id
            AND izklant.model = 'Klant'
            AND (izklant.iz_afsluiting_id IS NULL OR izklant.iz_afsluiting_id != 10)
            INNER JOIN klanten ON izklant.foreign_key = klanten.id
            INNER JOIN iz_koppelingen aanbod ON aanbod.id = vraag.iz_koppeling_id
            INNER JOIN iz_deelnemers izvrijwilliger ON izvrijwilliger.id = aanbod.iz_deelnemer_id
            AND izvrijwilliger.model = 'Vrijwilliger'
            AND (izvrijwilliger.iz_afsluiting_id IS NULL OR izvrijwilliger.iz_afsluiting_id != 10)
            INNER JOIN vrijwilligers ON izvrijwilliger.foreign_key = vrijwilligers.id
            {$condition}
            GROUP BY p.naam";
    }

    private function count_per_stadsdeel($condition)
    {
        return "SELECT COUNT(DISTINCT vraag.id) AS aantal, s.stadsdeel
            FROM iz_koppelingen vraag
            INNER JOIN iz_deelnemers izklant ON izklant.id = vraag.iz_deelnemer_id
            AND izklant.model = 'Klant'
            AND (izklant.iz_afsluiting_id IS NULL OR izklant.iz_afsluiting_id != 10)
            INNER JOIN klanten ON izklant.foreign_key = klanten.id
            LEFT JOIN stadsdelen s ON s.postcode = klanten.postcode
            INNER JOIN iz_koppelingen aanbod ON aanbod.id = vraag.iz_koppeling_id
            INNER JOIN iz_deelnemers izvrijwilliger ON izvrijwilliger.id = aanbod.iz_deelnemer_id
            AND izvrijwilliger.model = 'Vrijwilliger'
            AND (izvrijwilliger.iz_afsluiting_id IS NULL OR izvrijwilliger.iz_afsluiting_id != 10)
            INNER JOIN vrijwilligers ON izvrijwilliger.foreign_key = vrijwilligers.id
            {$condition}
            GROUP BY s.stadsdeel";
    }

    private function count_per_postcodegebied($condition)
    {
        return "SELECT COUNT(DISTINCT vraag.id) AS aantal, pc.postcodegebied
            FROM iz_koppelingen vraag
            INNER JOIN iz_deelnemers izklant ON izklant.id = vraag.iz_deelnemer_id
                AND izklant.model = 'Klant'
                AND (izklant.iz_afsluiting_id IS NULL OR izklant.iz_afsluiting_id != 10)
            INNER JOIN klanten ON izklant.foreign_key = klanten.id
            LEFT JOIN postcodegebieden pc ON SUBSTRING(klanten.postcode, 1, 4) BETWEEN pc.van AND pc.tot
            INNER JOIN iz_koppelingen aanbod ON aanbod.id = vraag.iz_koppeling_id
            INNER JOIN iz_deelnemers izvrijwilliger ON izvrijwilliger.id = aanbod.iz_deelnemer_id
                AND izvrijwilliger.model = 'Vrijwilliger'
                AND (izvrijwilliger.iz_afsluiting_id IS NULL OR izvrijwilliger.iz_afsluiting_id != 10)
            INNER JOIN vrijwilligers ON izvrijwilliger.foreign_key = vrijwilligers.id
            {$condition}
            GROUP BY pc.postcodegebied";
    }

    private function count_per_project_stadsdeel($condition)
    {
        return "SELECT COUNT(DISTINCT vraag.id) AS aantal, p.naam AS project, s.stadsdeel
            FROM iz_koppelingen vraag
            INNER JOIN iz_projecten p ON p.id = vraag.project_id
            INNER JOIN iz_deelnemers izklant ON izklant.id = vraag.iz_deelnemer_id
            AND izklant.model = 'Klant'
            AND (izklant.iz_afsluiting_id IS NULL OR izklant.iz_afsluiting_id != 10)
            INNER JOIN klanten ON izklant.foreign_key = klanten.id
            LEFT JOIN stadsdelen s ON s.postcode = klanten.postcode
            INNER JOIN iz_koppelingen aanbod ON aanbod.id = vraag.iz_koppeling_id
            INNER JOIN iz_deelnemers izvrijwilliger ON izvrijwilliger.id = aanbod.iz_deelnemer_id
            AND izvrijwilliger.model = 'Vrijwilliger'
            AND (izvrijwilliger.iz_afsluiting_id IS NULL OR izvrijwilliger.iz_afsluiting_id != 10)
            INNER JOIN vrijwilligers ON izvrijwilliger.foreign_key = vrijwilligers.id
            {$condition}
            GROUP BY p.naam, s.stadsdeel";
    }

    private function count_per_project_postcodegebied($condition)
    {
        return "SELECT COUNT(DISTINCT vraag.id) AS aantal, p.naam AS project, pc.postcodegebied
            FROM iz_koppelingen vraag
            INNER JOIN iz_projecten p ON p.id = vraag.project_id
            INNER JOIN iz_deelnemers izklant ON izklant.id = vraag.iz_deelnemer_id
            AND izklant.model = 'Klant'
            AND (izklant.iz_afsluiting_id IS NULL OR izklant.iz_afsluiting_id != 10)
            INNER JOIN klanten ON izklant.foreign_key = klanten.id
            LEFT JOIN postcodegebieden pc ON SUBSTRING(klanten.postcode, 1, 4) BETWEEN pc.van AND pc.tot
            INNER JOIN iz_koppelingen aanbod ON aanbod.id = vraag.iz_koppeling_id
            INNER JOIN iz_deelnemers izvrijwilliger ON izvrijwilliger.id = aanbod.iz_deelnemer_id
            AND izvrijwilliger.model = 'Vrijwilliger'
            AND (izvrijwilliger.iz_afsluiting_id IS NULL OR izvrijwilliger.iz_afsluiting_id != 10)
            INNER JOIN vrijwilligers ON izvrijwilliger.foreign_key = vrijwilligers.id
            {$condition}
            GROUP BY p.naam, pc.postcodegebied";
    }

    private function beginstand(DateTime $startDate)
    {
        return "WHERE vraag.koppeling_startdatum < '{$startDate->format('Y-m-d')}'
            AND (vraag.koppeling_einddatum IS NULL OR vraag.koppeling_einddatum >= '{$startDate->format('Y-m-d')}')
            AND (vraag.iz_eindekoppeling_id IS NULL OR vraag.iz_eindekoppeling_id != 10)";
    }

    private function gestart(DateTime $startDate, DateTime $endDate)
    {
        return "WHERE vraag.koppeling_startdatum >= '{$startDate->format('Y-m-d')}'
            AND vraag.koppeling_startdatum <= '{$endDate->format('Y-m-d')}'
            AND (vraag.iz_eindekoppeling_id IS NULL OR vraag.iz_eindekoppeling_id != 10)";
    }

    private function afgesloten(DateTime $startDate, DateTime $endDate)
    {
        return "WHERE vraag.koppeling_einddatum >= '{$startDate->format('Y-m-d')}'
            AND vraag.koppeling_einddatum <= '{$endDate->format('Y-m-d')}'
            AND (vraag.iz_eindekoppeling_id IS NULL OR vraag.iz_eindekoppeling_id != 10)";
    }

    private function succesvol_afgesloten(DateTime $startDate, DateTime $endDate)
    {
        return "WHERE vraag.koppeling_einddatum >= '{$startDate->format('Y-m-d')}'
            AND vraag.koppeling_einddatum <= '{$endDate->format('Y-m-d')}'
            AND vraag.koppeling_succesvol = 1
            AND (vraag.iz_eindekoppeling_id IS NULL OR vraag.iz_eindekoppeling_id != 10)";
    }

    private function eindstand(DateTime $endDate)
    {
        return "WHERE vraag.koppeling_startdatum <= '{$endDate->format('Y-m-d')}'
            AND (vraag.koppeling_einddatum IS NULL OR vraag.koppeling_einddatum > '{$endDate->format('Y-m-d')}')
            AND (vraag.iz_eindekoppeling_id IS NULL OR vraag.iz_eindekoppeling_id != 10)";
    }
}
