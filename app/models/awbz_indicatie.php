<?php

class AwbzIndicatie extends AppModel
{
    public $name = 'AwbzIndicatie';
    public $actsAs = array('FixDates');
    public $validate = array(
        'begindatum' => array(
            'date' => array(
                'rule' => array('date'),
                'message' => 'Datum',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'periodsDontOverlap' => array(
                'rule' => array('periodsDontOverlap'),
                'message' => 'Datums van indicaties mogen niet overlappen',
                ),
        ),
        'einddatum' => array(
            'date' => array(
                'rule' => array('date'),
                'message' => 'Datum',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'periodsDontOverlap' => array(
                'rule' => array('periodsDontOverlap'),
                'message' => 'Datums van indicaties mogen niet overlappen',
                ),

        ),
        'begeleiding_per_week' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'activering_per_week' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'hoofdaannemer_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    public $belongsTo = array(
        'Klant' => array(
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
        ),
        'Hoofdaannemer' => array(
            'className' => 'Hoofdaannemer',
            'foreignKey' => 'hoofdaannemer_id',
            'order' => 'Hoofdaannemer.naam ASC',
        ),
        'Aangevraagd' => array(
            'className' => 'Medewerker',
            'foreignKey' => 'aangevraagd_id',
        ),
    );

    public function periodsDontOverlap($val)
    {
        $d = &$this->data[$this->alias];

        if (!isset($d['klant_id'])) {
            return true;
        }

        foreach ($val as $field => $date) {
        }

        $conditions = array(
                'begindatum <=' => $date, 'einddatum >=' => $date,
                'klant_id' => $d['klant_id'],
            );

        if (!empty($d['id'])) {
            $conditions['NOT'] = array('id' => $d['id']);
        }

        $hits = $this->find('list', array('conditions' => $conditions));

        if ($hits) {
            return false;
        }

        return true;
    }

    public function getLatestAndCloseToExpireForEachClient($options = array())
    {
        if (isset($options['geslacht']) && !empty($options['geslacht'])) {
            App::import('Sanitize');
            $val = Sanitize::escape($options['geslacht']);
            $geslacht_condition = 'AND Klant.geslacht_id = '.$val;
        } else {
            $geslacht_condition = '';
        }

        $sql_query = '
			SELECT
				AwbzIndicatie.id, AwbzIndicatie.klant_id,
				AwbzIndicatie.begindatum, AwbzIndicatie.einddatum,
				AwbzIndicatie.created, AwbzIndicatie.aangevraagd_id,
				AwbzIndicatie.aangevraagd_datum,
				CONCAT_WS(\' \',
					Medewerker.voornaam, Medewerker.tussenvoegsel, Medewerker.achternaam
					) AS aangevraagd_naam,
				`Klant`.`voornaam`, `Klant`.`roepnaam`,
				CONCAT_WS(\' \', `Klant`.`tussenvoegsel`, `Klant`.`achternaam`)
					AS name2nd_part
			FROM (
				SELECT * FROM awbz_indicaties
				ORDER BY
					awbz_indicaties.begindatum DESC,
					awbz_indicaties.created DESC
			) AS `AwbzIndicatie`
			LEFT JOIN
				klanten AS `Klant` ON AwbzIndicatie.klant_id = Klant.id
			LEFT JOIN
				medewerkers AS `Medewerker` ON Medewerker.id = AwbzIndicatie.aangevraagd_id
			WHERE
				! aangevraagd_niet
				AND einddatum <= "' .date('Y-m-d', strtotime('+75 days')).'"
				AND einddatum >= "' .date('Y-m-d', strtotime('today')).'"
				AND NOT EXISTS (SELECT * 
								  FROM awbz_indicaties ind2
								 WHERE ind2.einddatum > AwbzIndicatie.einddatum
								   AND ind2.klant_id = AwbzIndicatie.klant_id)
			'.$geslacht_condition.'
			GROUP BY
				klant_id;
		';

        $result = $this->query($sql_query);

        return $result;
    }

    public function getAbwzReportData($year, $month)
    {
        $thisMonthStartTs = mktime(0, 0, 0, $month, 1, $year);
        $thisMonthStart = date('Y-m-d', $thisMonthStartTs);
        $nextMonthStartTs = strtotime('+1 month', $thisMonthStartTs);
        $nextMonthStart = date('Y-m-d', $nextMonthStartTs);
        $thisMonthEnd = date('Y-m-d', strtotime('-1 day', $nextMonthStartTs));

        $sql_query = "
			select klant_id,
				   klanten.voornaam, tussenvoegsel, achternaam, roepnaam, geboortedatum, BSN
				   hoofdaannemer_id, hoofdaannemers.naam,
				   round(sum((datediff(indicaties_end, indicaties_start) + 1) / 7 * begeleiding_per_week)) begeleiding,
				   round(sum((datediff(indicaties_end, indicaties_start) + 1) / 7 * activering_per_week)) activering,
				   round(sum(if(is_location_other, aanpassing_verslag, 0))) verslag_other_location,
				   round(sum(if(is_location_other, 0, aanpassing_verslag))) verslag_also_regisered,
				   round(sum(registratie)) registratie
			  from (select ind.klant_id,
						   hoofdaannemer_id,
						   least(greatest(cast('$thisMonthStart' as date), begindatum), cast('$thisMonthEnd' as date)) indicaties_start,
						   greatest(least(cast('$thisMonthEnd' as date), einddatum), cast('$thisMonthStart' as date)) indicaties_end,
						   begeleiding_per_week, activering_per_week,
						   (vers.locatie_id <=> 0) as is_location_other,
						   sum(vers.aanpassing_verslag/60) / greatest(1, coalesce(count(distinct reg.id), 1)) aanpassing_verslag,
						   sum(hour(timediff(reg.buiten, reg.binnen))) / greatest(1, coalesce(count(distinct vers.id), 1)) as registratie
					  from awbz_indicaties ind
					  left join verslagen vers 
						on (vers.klant_id = ind.klant_id
					   and vers.datum >= '$thisMonthStart'
					   and vers.datum < '$nextMonthStart'
					   and vers.datum >= ind.begindatum
					   and vers.datum <= ind.einddatum
						   )
					  left join registraties reg 
						on (reg.klant_id = ind.klant_id
					   and reg.binnen >= '$thisMonthStart'
					   and reg.binnen < '$nextMonthStart'
					   and reg.binnen >= ind.begindatum
					   and date(reg.buiten) <= ind.einddatum
						   )
					 where ind.begindatum < '$nextMonthStart'
					   and ind.einddatum >= '$thisMonthStart'
					   and vers.contactsoort_id = 3
					 group by klant_id, hoofdaannemer_id, indicaties_start, indicaties_end, begeleiding_per_week, activering_per_week, (vers.locatie_id <=> 0)
					 ) Info
			  join klanten on (Info.klant_id = klanten.id)
			  join hoofdaannemers on (Info.hoofdaannemer_id = hoofdaannemers.id)
			 group by hoofdaannemers.naam, achternaam, voornaam, tussenvoegsel, klant_id, roepnaam, geboortedatum, BSN, hoofdaannemer_id";
        $result = $this->query($sql_query);

        return $result;
    }
}
