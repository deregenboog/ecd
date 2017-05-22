<?php

class RapportagesController extends AppController
{
    public $name = 'Rapportages';
    public $uses = array('Klant', 'Locatie', 'Registratie', 'Schorsing');
    public $components = array('Filter', 'Session', 'SqlReport');
    public $helpers = array('Time');

    public function beforeFilter()
    {
        //$this->Klant->showDisabled = true;
        parent::beforeFilter();
    }

    public function index()
    {
        // Index only displays a view
    }

    public function lijst()
    {
        $this->paginate = array(
            'contain' => array(
                'LasteIntake' => array(
                    'fields' => array(
                        'locatie1_id',
                        'locatie2_id',
                        'locatie3_id',
                        'datum_intake',
                    ),
                ),
                'Intake' => array(
                    'fields' => array('datum_intake', 'id'),
                ),
                'Geslacht', ),
        );
        $this->Klant->showDisabled = false;
        $this->set('filter_options', $this->Filter->filterData);
        $klanten = $this->paginate('Klant', $this->Filter->filterData);
        $klanten = $this->Klant->LasteIntake->completeKlantenIntakesWithLocationNames($klanten);
        $rowOnclickUrl = array('controller' => 'rapportages', 'action' => 'klant');
        $this->set(compact('klanten', 'rowOnclickUrl'));

        if ($this->RequestHandler->isAjax()) {
            $this->render('/elements/klantenlijst', 'ajax');
        } else ;
    }

    public function klant($id = null)
    {
        if (empty($id)) { //when there's no client we redirect to the list of clients
            $this->flashError(__('Invalid klant', true));
            $this->redirect(array('action' => 'lijst'));

            return;
        }

        // Set extra constraints if user has given a date-range
        $con = [];
        $consusp = [];
        $date_from = null;
        $date_to = null;
        $count = null;
        $countLocation = null;
        $lastRegistration = null;

        //$this->Klant->recursion = -1;
        $klant = $this->Klant->findById($id);

        if ($this->data) {

            //relies on $this->data
            $this->_prepare_dates($date_from, $date_to);

            $con = array(
                'binnen >=' => $date_from,
                'binnen <' => $this->_add_day($date_to),
            );
            $consusp = array('Schorsing.datum_van >=' => $date_from,
                'Schorsing.datum_van <=' => $date_to, );

            //$klant = $this->Klant->find('first', array('conditions' => array('Klant.id' => $id), 'contain' => 'Klant'));

            $count['visits'] = $this->Registratie->find('count', array('conditions' => array_merge($con, array('klant_id' => $id))));
            $count['shower'] = $this->Registratie->find('count', array('conditions' => array_merge($con, array('klant_id' => $id, 'douche' => 1))));
            $count['clothes'] = $this->Registratie->find('count', array('conditions' => array_merge($con, array('klant_id' => $id, 'kleding' => 1))));
            $count['meals'] = $this->Registratie->find('count', array('conditions' => array_merge($con, array('klant_id' => $id, 'maaltijd' => 1))));
            $count['activation'] = $this->Registratie->find('count', array('conditions' => array_merge($con, array('klant_id' => $id, 'activering' => 1))));
            $count['suspension'] = $this->Schorsing->find('count', array('conditions' => array_merge($consusp, array('klant_id' => $id))));
            $lastRegistration = $this->Registratie->find('first', array(
                        'conditions' => array_merge($con, array('klant_id' => $id)),
                        'fields' => array('max(binnen) as max'),
                        ));
            $countLocation = $this->Registratie->find('all', array(
                        'fields' => array('Locatie.naam', 'count(1) as count'),
                        'group' => 'locatie_id',
                        'conditions' => array_merge($con, array('Registratie.klant_id' => $id)), ));
        } else {
            $this->data['date_from'] = date('Y-m-d', time() - YEAR);
            $this->data['date_to'] = date('Y-m-d', time() - DAY);
        }
        $this->set('lastRegistration', $lastRegistration);

        $this->set('klant', $klant);
        $this->set('count', $count);
        $this->set('countLocation', $countLocation);
        $this->set('daterange', $this->data);
        $this->set('date_from', $date_from);
        $this->set('date_to', $date_to);
    }

    public function _generateInfobalieStats($date_from, $date_to)
    {
        $klant_cond = [];
        $klant_created_cond = [];
        $geslacht_cond = [];
        $intake_cond = [];
        $locatie_cond = [];

        $date_cond = array(
                'binnen >=' => $date_from,
                'binnen <' => $this->_add_day($date_to),
                );
        $verslagen_dates = array(
                'datum >=' => $date_from,
                'datum <' => $this->_add_day($date_to),
                );

        $klant_created_cond =
            array('Klant.created <' => $this->_add_day($date_to));

        if (
                !empty($this->data['options']['geslacht_id']) &&
                $this->data['options']['geslacht_id'] != 0
          ) {
            $geslacht_cond = array(
                    'Klant.geslacht_id' => $this->data['options']['geslacht_id'],
                    );
        }

        if (isset($this->data['options']) && $this->data['options']['location'] != 0) {
            $locatie_id = $this->data['options']['location'];
            $locatie_cond = array('locatie_id' => $locatie_id);
            $locatie_intake_cond = array('locatie2_id' => $locatie_id);
            $intake_cond['OR'] = array(
                    'locatie1_id' => $locatie_id,
                    'locatie2_id' => $locatie_id,
                    'locatie3_id' => $locatie_id,
                    );
        }

        $landen_cond = array('land_id' => $this->data['options']['land_id']);

        if (empty($this->data['options']['land_id'])) {
            $this->flashError(__('No country selected', true));
        }

        $this->Klant->recursive = 0;

    // We take out the intake_cond, so we always find all klanten regardless of the location choosen
    // this collection we have to filter down based on the actual registraties
        $klanten = $this->Klant->find('all', array(
                    'conditions' => $landen_cond + $geslacht_cond
                    + $klant_created_cond,
                    'fields' => array('id', 'laste_intake_id'), ));

        $klanten = Set::combine($klanten, '{n}.Klant.id', '{n}.Klant.laste_intake_id');
        $klanten_list_all = array_keys($klanten);
        $klanten_id_cond = array('klant_id' => $klanten_list_all);

        if (!empty($locatie_cond)) {
            $registraties = $this->Registratie->find('all', array(
            'fields' => array('klant_id'),
            'conditions' => $klanten_id_cond + $locatie_cond + $date_cond,
            //$klanten_id_cond + $locatie_cond
        ));

            $klanten_list = array_unique(Set::ClassicExtract($registraties, '{n}.Registratie.klant_id'));
            $klanten_list_verslag = [];
            $verslagen = [];
            $verslagen = $this->Klant->Verslag->find('list',
                    array(
                        'conditions' => $klanten_id_cond + $locatie_cond + $verslagen_dates, 'fields' => array('id', 'klant_id'),
                    )
                );

            $klanten_list_verslag = array_unique(Set::ClassicExtract($verslagen, '{n}.Verslag.klant_id'));

            $intakes = [];
            $intakes = $this->Klant->Intake->find('list',
                    array(
                        'conditions' =>
                        //$klanten_id_cond + $locatie_intake_cond + $verslagen_dates
                        $klanten_id_cond + $locatie_intake_cond, 'fields' => array('id', 'klant_id'),
                    )
                );

            $klanten_list_intake = array_unique(Set::ClassicExtract($intakes, '{n}.Intake.klant_id'));
            $tmp_klanten = $klanten;
            $klanten = [];
            foreach ($tmp_klanten as $klant_id => $laste_intake_id) {
                if (in_array($klant_id, $klanten_list_intake)) {
                    $klanten[$klant_id] = $laste_intake_id;
                    continue;
                }
                if (in_array($klant_id, $klanten_list)) {
                    $klanten[$klant_id] = $laste_intake_id;
                    continue;
                }
                if (in_array($klant_id, $klanten_list_verslag)) {
                    $klanten[$klant_id] = $laste_intake_id;
                    continue;
                }
            }
        //debug(count($tmp_klanten));
        //debug(count($klanten_list));
        //debug(count($klanten_list_verslag));
        //debug(count($klanten)) ;
        }
        $klanten_id_cond = array('klant_id' => array_keys($klanten));
        $klanten_cond = array('id' => array_keys($klanten));

        $count['amoc_landen'] = $this->Klant->Geboorteland->find('list',
                array('conditions' => array('id' => $this->data['options']['land_id']),
                    'order' => 'land', ));

        $count['primaireproblematiek'] = $this->Klant->Intake->PrimaireProblematiek->find('list');

        $count['doorverwijzers'] = $this->Klant->Verslag->InventarisatiesVerslagen->Doorverwijzer->find('list');

        $count['totalClients'] = count($klanten);
        $this->Klant->recursive = -1;
        $count['totalNewClients'] = $this->Klant->find('count',
                array('conditions' => $klanten_cond
                    + array('created >=' => $date_from), ));

        $count['uniqueVisits'] = $this->Registratie->find('count', array(
                    'fields' => array('COUNT(DISTINCT Registratie.klant_id) AS count'),
                    'conditions' => $klanten_id_cond + $locatie_cond + $date_cond,
                    ));
        $count['totalVisits'] = $this->Registratie->find('count', array(
                    'conditions' => $klanten_id_cond + $locatie_cond + $date_cond,
                    ));

        $verslagen = $this->Klant->Verslag->find('list',
                array(
                    'conditions' => $klanten_id_cond + $locatie_cond + $verslagen_dates, 'fields' => array('id', 'klant_id'),
                    )
                );

        $count['totalVerslagen'] = count($verslagen);

        $dvw_cond = array('verslag_id' => array_keys($verslagen));

        $this->Klant->Verslag->InventarisatiesVerslagen->recursive = -1;
        $dvw = $this->Klant->Verslag->InventarisatiesVerslagen->find('all',
                array('conditions' => $dvw_cond,
                    'fields' => array('doorverwijzer_id', 'COUNT(id) as count'),
                    'group' => 'doorverwijzer_id',
                    )
                );

        $count['count_per_doorverwijzers'] = Set::combine($dvw, '{n}.InventarisatiesVerslagen.doorverwijzer_id', '{n}.0.count');
        $count['doorverwijzers_count'] = array_sum($count['count_per_doorverwijzers']);

        $this->Klant->recursive = -1;

        $countries = $this->Klant->find('all', array(
                    'conditions' => $klanten_cond,
                    'fields' => array('land_id', 'COUNT(id) as count'),
                    'order' => 'land_id',
                    'group' => 'land_id', ));

        $count['clientsPerCountry'] = Set::combine($countries, '{n}.Klant.land_id', '{n}.0.count');

        $birthdates = $this->Klant->find('all', array(
                    'conditions' => $klanten_cond,
                    'fields' => array('YEAR(geboortedatum) as year', 'COUNT(id) as count'),
                    'group' => '1',
                    'order' => 'year', ));

        $ages = $this->Klant->find('all', array(
                    'conditions' => $klanten_cond,
                    'fields' => array("DATE_FORMAT(FROM_DAYS(TO_DAYS('$date_to')-TO_DAYS(geboortedatum)), '%Y')+0 as age", 'COUNT(id) as count'),
                    'group' => '1',
                    'order' => 'age', ));

        $count['birthdates'] = Set::combine($birthdates, '{n}.0.year', '{n}.0.count');
        $count['ages'] = Set::combine($ages, '{n}.0.age', '{n}.0.count');

        $sum_ages = 0;
        $cnt_ages = 0;
        foreach ($count['ages'] as $age => $cnt) {
            if (empty($age)) {
                continue;
            } // exclude null values from the average
            $cnt_ages += $cnt;
            $sum_ages += $age * $cnt;
        }
        if ($cnt_ages > 0) {
            $count['averageAge'] = round($sum_ages / $cnt_ages, 1);
        } else {
            $count['averageAge'] = '--';
        }

        $this->Klant->Intake->recursive = -1;

        $problems = $this->Klant->Intake->find('all', array('conditions' => array('Intake.id' => $klanten),
                    'fields' => array('primaireproblematiek_id as problem',
                        'COUNT(Intake.id) as count', ),
                    'group' => '1',
                    'order' => 'problem', ));

        $count['primaryProblems'] = Set::combine($problems, '{n}.Intake.problem', '{n}.0.count');
    //debug($count);

        return $count;
    }

    public function infobalie()
    {
        // Gather data for a location specific report
        $con = [];

        $references = array('het voorgande jaar', 'het afgelope jaar', 'dezelfde periode een jaar eerder');

        $amocCountries = Configure::read('Landen.AMOC');
        $landen = $this->Klant->Geboorteland->find('list', array('conditions' => array('id' => $amocCountries), 'order' => 'land'));
        $landen += array(1 => '1');

        // Set extra constraints if user has given a location and/or date-range
        $date_from = null;
        $date_to = null;
        $ref_to = null;
        $ref_from = null;
        $count = null;
        $ref = null;

        if ($this->data) {
            $this->_prepare_dates($date_from, $date_to);
            $count = $this->_generateInfobalieStats($date_from, $date_to);

            list($ref_from, $ref_to) = $this->_prepare_ref_dates(
                $this->data['options']['reference_id'], $date_from, $date_to);
            $ref = $this->_generateInfobalieStats($ref_from, $ref_to);
        } else {
            $this->data = array(
                'date_from' => array('year' => date('Y'), 'month' => '01',
                    'day' => '01', ),
                'date_to' => date('Y-m-d', time() - DAY),
                    );
        }

        $this->Locatie->recursive = -1;
        $locations = $this->Locatie->find('list', array('fields' => array('Locatie.id', 'Locatie.naam')));

        $this->set(compact(
            'locations', 'date_to', 'date_from', 'ref_from', 'ref_to',
            'count', 'ref', 'landen', 'references'
        ));
    }

    public function locatie_nieuwe_klanten()
    {
        $date_from = mysql_escape_string($this->getParam('date_from'));
        $date_until = mysql_escape_string($this->getParam('date_until'));
        $geslacht_id = mysql_escape_string($this->getParam('geslacht_id'));
        $locatie_id = mysql_escape_string($this->getParam('locatie_id'));

        $where = " binnen >= '{$date_from}' and binnen < '{$date_until}' ";
        $where .= " and k.created >= '{$date_from}' and k.created < '{$date_until}' ";

        if (!empty($locatie_id)) {
            $where .= " and locatie_id = {$locatie_id} ";
        }
        if (!empty($geslacht_id)) {
            $where .= " and geslacht_id = {$geslacht_id} ";
        }

        $qu = "select k.id as klant_id, voornaam, tussenvoegsel, achternaam
                    from klanten k join  registraties r on r.klant_id = k.id where {$where} group by k.id ";

        $select = $this->Klant->query($qu);
        $title = 'Nieuwe klanten';
        $this->set(compact('select', 'date_from', 'date_until', 'title'));
        $this->layout = false;
        $file = "nieuwe_klanten_{$date_from}_{$date_until}.xls";
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$file\";");
        header('Content-Transfer-Encoding: binary');
        $this->render('locatie_report_excel');

        //new clients conditions
    }

    public function locatie()
    {
        // Gather data for a location specific report
        $con = [];
        $consusp = [];

        // Set extra constraints if user has given a location and/or date-range
        $date_from = null;
        $date_to = null;
        $date_until = null;
        $geslacht_id = null;
        $locatie_id = null;
        $klant_cond = [];
        $geslacht_cond = [];
        $intake_cond = [];

        if ($this->data) {
            $this->_prepare_dates($date_from, $date_to);

            $con = array(
                'binnen >=' => $date_from,
                'binnen <' => $this->_add_day($date_to),
            );
            $consusp = array(
                'Schorsing.datum_van >=' => $date_from,
                //this is a date field so it doesn't have to be incremented by
                //one day as it has no time and <= operator will return the
                //correct values
                'Schorsing.datum_van <=' => $date_to,
            );
            $date_until = mysql_escape_string($this->_add_day($date_to));
            $date_from = mysql_escape_string($date_from);

            $where = " binnen >= '{$date_from}' and binnen < '{$date_until}' ";

            if (isset($this->data['options']) && $this->data['options']['location'] != 0) {
                $locatie_id = mysql_escape_string($this->data['options']['location']);
                $where .= " and locatie_id = {$locatie_id} ";
            }
            if (!empty($this->data['options']['geslacht_id']) && $this->data['options']['geslacht_id'] != 0) {
                $geslacht_id = mysql_escape_string($this->data['options']['geslacht_id']);
                $where .= " and geslacht_id = {$geslacht_id} ";
            }

            $qu = "create temporary table tmp_registrations select k.id as klant_id, voornaam, tussenvoegsel, achternaam, douche, kleding, maaltijd, activering, locatie_id, k.created, binnen  from klanten k join  registraties r on r.klant_id = k.id where {$where} ";

            $this->Klant->query($qu);

            //new clients conditions
            $klant_cond = array(
                'Klant.created >=' => $date_from,
                'Klant.created <' => $this->_add_day($date_to),
            );
            //intake
            $intake_cond = array(
                'Intake.created >=' => $date_from,
                'Intake.created <' => $this->_add_day($date_to),
            );

            if (
                !empty($this->data['options']['geslacht_id']) &&
                $this->data['options']['geslacht_id'] != 0
            ) {
                $geslacht_cond = array(
                    'Klant.geslacht_id' => $this->data['options']['geslacht_id'],
                );
            }
        }

        if (isset($this->data['options']) && $this->data['options']['location'] != 0) {
            $locatie_id = $this->data['options']['location'];
            $con = array_merge($con, array('locatie_id' => $locatie_id));
            $consusp = array_merge($consusp, array('locatie_id' => $locatie_id));
            $klant_cond['OR'] = array(
                'LasteIntake.locatie1_id' => $locatie_id,
                'LasteIntake.locatie2_id' => $locatie_id,
                'LasteIntake.locatie3_id' => $locatie_id,
            );
            $intake_cond['OR'] = array(
                'Intake.locatie1_id' => $locatie_id,
                'Intake.locatie2_id' => $locatie_id,
                'Intake.locatie3_id' => $locatie_id,
            );
        }

        if ($this->data) {
            $con = array_merge($con, $geslacht_cond);
            $consusp = array_merge($consusp, $geslacht_cond);
            $intake_cond = $intake_cond + $geslacht_cond;

            // We now change the report and use the temporary table
            $count['uniqueVisits'] = $this->Registratie->find('count', array(
                'fields' => array('COUNT(DISTINCT Registratie.klant_id) AS count'),
                'conditions' => $con,
            ));

//             $count['totalVisits'] = $this->Registratie->find('count', array(
//                 'conditions' => $con
//             ));
            $q = 'select count(*) as cnt from tmp_registrations';
            $r = $this->Klant->query($q);
            $count['totalVisits'] = $r[0][0]['cnt'];

            $q = 'select sum(abs(douche)) as douche, sum(kleding) as kleding, sum(maaltijd) as maaltijd, sum(activering) as activering  from tmp_registrations';
            $r = $this->Klant->query($q);
            $count['shower'] = $r[0][0]['douche'];
            $count['clothes'] = $r[0][0]['kleding'];
            $count['meals'] = $r[0][0]['maaltijd'];
            $count['activation'] = $r[0][0]['activering'];

            $count['suspensions'] = $this->Schorsing->find('count', array('conditions' => array_merge($consusp)));
            $count['intakes'] = $this->Klant->Intake->find('count', array('conditions' => $intake_cond));

            $q = 'select count(distinct klant_id) as cnt from tmp_registrations ';
            $r = $this->Klant->query($q);
            $count['unique_visitors'] = $r[0][0]['cnt'];

            $q = 'select count(*) as cnt from (select count(*) as cnt from tmp_registrations group by klant_id having cnt >= 4 ) as subq';
            $r = $this->Klant->query($q);
            $count['unique_visitors_4_or_more_visits'] = $r[0][0]['cnt'];

            //debug(array_merge($klant_cond, $geslacht_cond));
            $q = "select count(*) as cnt from (select klant_id from tmp_registrations where  created >= '{$date_from}' and created < '{$date_until}' group by klant_id) as subq ";
            $r = $this->Klant->query($q);
            $count['new_clients'] = $r[0][0]['cnt'];

            $q = 'select klant_id, group_concat(locatie_id) from tmp_registrations group by  klant_id ';
            $q = 'select naam, count(*) as cnt from (select klant_id, locatie_id, count(*) as cnt from tmp_registrations group by  klant_id, locatie_id ) as subq join locaties l on l.id = locatie_id group by locatie_id';
            $r = $this->Klant->query($q);
            $unique_per_location = $r;

            $this->set(compact(
                'date_to', 'date_from', 'count', 'unique_per_location'
            ));
        }

        $this->Locatie->recursive = -1;
        $locations = $this->Locatie->find('list', [
            'fields' => ['Locatie.id', 'Locatie.naam'],
            // @link https://github.com/deregenboog/ecd/issues/47
//             'conditions' => ['OR' => [
//                 ['datum_tot' => '0000-00-00'],
//                 ['datum_tot >' => date('Y-m-d')],
//             ]],
        ]);

        $this->set(compact('locations', 'date_from', 'date_until', 'geslacht_id', 'locatie_id'));
    }

    public function locatie_klant()
    {
        // Gather data for a location specific report
        $con = [];

        // Set extra constraints if user has given a location and/or date-range
        $date_from = null;
        $date_to = null;
        $current_location = 'Alle locaties';
        $geslacht_cond = [];

        if ($this->data) {
            $this->_prepare_dates($date_from, $date_to);

            $con = array(
                'binnen >=' => $date_from,
                'binnen <' => $this->_add_day($date_to),
            );

            if (isset($this->data['options']) && $this->data['options']['location'] != 0) {
                $con = array_merge($con, array('locatie_id' => $this->data['options']['location']));
                $current_location = $this->data['options']['location'];
            }

            if (
                !empty($this->data['options']['geslacht_id']) &&
                $this->data['options']['geslacht_id'] != 0
            ) {
                $geslacht_cond = array(
                    'Klant.geslacht_id' => $this->data['options']['geslacht_id'],
                );
            }
        }//if data exists
        $this->Registratie->Behaviors->attach('Containable');

        $registratie_counts = $this->Registratie->find('all', array(
            'conditions' => array_merge($con, $geslacht_cond),
            'contain' => array(
                'Klant' => array(
                    'fields' => array(
                        'CONCAT_WS(\' \', `Klant`.`voornaam`, `Klant`.`tussenvoegsel`, `Klant`.`achternaam`) as name',
                        'roepnaam',
                        ),
                    ),
                ),
            'order' => 'Registratie.klant_id',
            'fields' => array('count(*) as cnt'),
            'group' => array('klant_id'),
        ));

        //setting stuff
        $locations = $this->Locatie->find('list', array('fields' => array('Locatie.id', 'Locatie.naam')));

        $this->set(compact('current_location', 'locations', 'date_to', 'date_from', 'registratie_counts'));
    }//locatie_klant

    public function schorsingen()
    {
        $conditions = [];
        $date_from = date('Y-m-d', strtotime('today - 1 year'));
        $date_to = date('Y-m-d', strtotime('tomorrow'));
        $current_location = 'Alle locaties';

        if ($this->data) {

        //setting the conditions depending on the data recieved from the form

        //location
            if (isset($this->data['options']) &&
                !empty($this->data['options']['location'])
            ) {
                $current_location = $this->data['options']['location'];
                $conditions['Schorsing.locatie_id'] = $current_location;
            }

        //dates
            $this->_prepare_dates($date_from, $date_to);
            // These conditions are to retrieve people who are suspended during
            // this interval, not for suspesions that start withing the given
            // frame. It makes more sense to me. J.V.
            $conditions['Schorsing.datum_van <='] = $date_to;
            $conditions['Schorsing.datum_tot >='] = $date_from;

        //gender
            if (!empty($this->data['options']['geslacht_id'])) {
                $conditions['Klant.geslacht_id'] =
                    $this->data['options']['geslacht_id'];
            }
        }

        // Strange query: get Schorsingen, and reorganize them per klant!

        $schorsingen = $this->Klant->Schorsing->find('all', array(
            'conditions' => $conditions,
            'order' => 'Schorsing.klant_id',
        ));

        //counting all schorsingen and active schorsingen for each client
        if (!empty($schorsingen)) {
            $clients = [];
            $previous_klant_id = null;
            foreach ($schorsingen as &$schorsing) {
                //when this iteration is over the same client as previous iteration:
                if ($previous_klant_id == $schorsing['Klant']['id']) {
                    $clients[$previous_klant_id]['total_sch'] += 1;
                } else {
                    //if the client has changed since the last iteration
                    //set the new id:
                    $previous_klant_id = $schorsing['Klant']['id'];
                    //create an array index for the client
                    $clients[$previous_klant_id] = array(
                        'total_sch' => 1,
                        'active_sch' => 0,
                        'name' => $schorsing['Klant']['name'],
                        'roepnaam' => $schorsing['Klant']['roepnaam'],
                    );
                }
                $clients[$previous_klant_id]['Schorsing'][] = $schorsing;

                //increment the active schorsingen if needed:
                if (
                    empty($schorsing['Schorsing']['datum_tot']) ||
                    $schorsing['Schorsing']['datum_tot'] > $date_to
                ) {
                    $clients[$previous_klant_id]['active_sch'] += 1;
                }
            }//end of foreach
        }//end of if empty

        //setting stuff
        $locations = $this->Locatie->find('list', array('fields' => array('Locatie.id', 'Locatie.naam')));

        $this->set(compact('current_location', 'locations', 'date_to', 'date_from', 'clients'));
    }

    public function awbz_indicaties()
    {
        $conditions = [];

        if ($this->data) {
            //setting the conditions depending on the data recieved from the form

        //gender
            if (!empty($this->data['options']['geslacht_id'])) {
                $conditions['geslacht'] =
                    $this->data['options']['geslacht_id'];
            }
        }

        $indicaties =
            $this->Klant->AwbzIndicatie->getLatestAndCloseToExpireForEachClient($conditions);

        //setting stuff
        $this->set(compact('indicaties'));
    }

    public function awbz_hoofdaannemers()
    {
        $this->Klant->AwbzIndicatie->recursive = -1;
        $data = $this->Klant->AwbzIndicatie->find('all', array(
            'joins' => array(
                array(
                    'table' => 'klanten',
                    'alias' => 'Klant',
                    'type' => 'left',
                    'foreignKey' => false,
                    'conditions' => array('AwbzIndicatie.klant_id = Klant.id'),
                ),
                array(
                    'table' => 'hoofdaannemers',
                    'alias' => 'Hoofdaannemer',
                    'type' => 'left',
                    'foreignKey' => false,
                    'conditions' => array('AwbzIndicatie.hoofdaannemer_id = Hoofdaannemer.id'),
                ),
            ),
            'fields' => array('AwbzIndicatie.*', 'Klant.*', 'Hoofdaannemer.naam'),
            'order' => 'AwbzIndicatie.hoofdaannemer_id, Klant.id',
            'limit' => 20,
        ));

        //The details query for each client is separate as this piece of code\
        //doesn't have to be so fast and the query combining it all together
        //would be very complicated

        $this->Klant->Registratie->virtualFields = array(
            'total_seconds' => 'sum(time_to_sec(timediff(buiten,binnen)))',
        );
        $this->Klant->Verslag->virtualFields = array(
            'total_minutes' => 'sum(Verslag.aanpassing_verslag)',
        );
        $contain = array(
            'Registratie' => array('total_seconds'),
            'Verslag' => array('total_minutes'),
        );

        foreach ($data as &$row) {
            $klant_id = $row['Klant']['id'];
            $klant =
                $this->Klant->find('all', array(
                    'conditions' => array('Klant.id' => $klant_id),
                    'contain' => $contain,
                ));
            $row['Klant']['name1st_part'] = $klant[0]['Klant']['name1st_part'];
            $row['Klant']['name2nd_part'] = $klant[0]['Klant']['name2nd_part'];
        //if the registration and the verslag times are there, calculate the
        //difference for the Begeleidingsuren genoten
            if (!empty($klant[0]['Registratie']) && !empty($klant[0]['Verslag'])) {
                $verslag_minutes = $klant[0]['Verslag'][0]['total_minutes'];
                $verslag_hours = round($verslag_minutes, 0);
                $reg_seconds = $klant[0]['Registratie'][0]['total_seconds'];
                $reg_minutes = $reg_seconds / 60;
                $reg_hours = round($reg_minutes, 0);
                $diff = $reg_hours - $verslag_hours;

                $row['Klant']['begeleidingsuren_genoten'] = $diff;
                $row['Klant']['activering_genoten'] = $verslag_hours;
            } else {
                $row['Klant']['begeleidingsuren_genoten'] = null;
                $row['Klant']['activering_genoten'] = null;
            }
        }
        //debug($data);
        $this->set('indicaties', $data);
    }

    /**
     * _prepare_ref_dates Reference dates are based on the input ones.
     *
     * @param mixed $ref_type
     * @param mixed $date_from
     * @param mixed $date_to
     *
     * @return array with the two reference dates
     */
    public function _prepare_ref_dates($ref_type, $date_from, $date_to)
    {
        $start = strtotime($date_from);
        $end = strtotime($date_to);
        switch ($ref_type) {
            case 0: // last year
                $last_year = date('Y', $start - YEAR);
                $start = strtotime('1 january '.$last_year);
                $end = strtotime('31 december '.$last_year);
            break;

            case 1: // whole year before
                $end = $start - DAY;
                $start = $end - YEAR + DAY;
            break;

            case 2: // same period, one year earlier
            default:
                $end = $end - YEAR;
                $start = $start - YEAR;
            break;
        }

        $ref_from = date('Y-m-d', $start);
        $ref_to = date('Y-m-d', $end);

        return array($ref_from, $ref_to);
    }

    //changes array dates into YYYY-MM-DD dates (needed by the db queries)
    //takes references to the date_from and date_to and does not touch then
    //if the dates in data are empty

    public function _prepare_dates(&$date_from, &$date_to)
    {
        //if there's no data, return
        if (empty($this->data)) {
            return;
        }

        //converting the date array into a string

        //which model we use doesn't matter here - we just need some name of
        //a date field to tell cake that we want the data to be
        //deconstructed into a date (not datetime)
        $from = $this->Klant->deconstruct(
            'geboortedatum', $this->data['date_from']);
        $to =
            $this->Klant->deconstruct('geboortedatum', $this->data['date_to']);

        if (!empty($from)) {
            $date_from = $from;
        }
        if (!empty($to)) {
            $date_to = $to;
        }
    }

    //adds given number of days to the date (one by default)

    public function _add_day($date, $number_of_days = 1)
    {
        return date('Y-m-d', strtotime("$date + $number_of_days days"));
    }

    public function management()
    {
        if (!$this->data) {
            $this->data = array(
                'date_from' => array('year' => date('Y', time() - YEAR), 'month' => '01', 'day' => '01'),
                'date_to' => array('year' => date('Y', time() - YEAR), 'month' => '12', 'day' => '31'),
            );
        }
        //dates
        $date_from = null;
        $date_to = null;
        $this->_prepare_dates($date_from, $date_to);
        $this->set(compact('date_from', 'date_to'));
    }

    /**
     * Management report.
     */
    public function ajaxManagement()
    {
        $conditions = [];

        //dates
        $date_from = null;
        $date_to = null;
        $this->_prepare_dates($date_from, $date_to);

        if (!$date_from || !$date_to) {
            $this->autoRender = false;

            return 'Bad dates!';
        }

        $conditions['from'] = "'".$date_from." 00:00:00'";
        $conditions['until'] = "'".$date_to." 23:59:59'";

        $reports = $this->_calculateManagementReport($conditions, 'management_reports.sql');

        $this->autoLayout = false;

        $this->set(compact('reports'));
        if (!empty($this->data['options']['excel'])) {
            $this->layout = false;
            $file = 'management_report.xls';
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=\"$file\";");
            header('Content-Transfer-Encoding: binary');
            //$this->log($reports,'reports');
            $this->render('management_excel');
        } else {
            // Why doesn't this view use a layout?
            // $this->layout = 'ajax';
        }
    }

    public function activering()
    {
        if (!$this->data) {
            $this->data = array(
                    'date_from' => array('year' => date('Y', time() - YEAR), 'month' => '01', 'day' => '01'),
                    'date_to' => array('year' => date('Y', time() - YEAR), 'month' => '12', 'day' => '31'),
            );
        }
        //dates
        $date_from = null;
        $date_to = null;
        $this->_prepare_dates($date_from, $date_to);
        $this->set(compact('date_from', 'date_to'));
    }

    /**
     * Management report.
     */
    public function ajaxActivering()
    {
        $conditions = [];

        //dates
        $date_from = null;
        $date_to = null;
        $this->_prepare_dates($date_from, $date_to);

        if (!$date_from || !$date_to) {
            $this->autoRender = false;

            return 'Bad dates!';
        }

        $conditions['from'] = "'".$date_from." 00:00:00'";
        $conditions['until'] = "'".$date_to." 23:59:59'";

        $reports = $this->_calculateManagementReport($conditions, 'activering_reports.sql');

        $this->set(compact('reports'));
        if (!empty($this->data['options']['excel'])) {
            $this->layout = false;
            $file = 'activering_report.xls';
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=\"$file\";");
            header('Content-Transfer-Encoding: binary');
            //$this->log($reports,'reports');
            $this->render('activering_excel');
        } else {
            $this->layout = 'ajax';
            $this->render('ajax_klanten');
        }
    }

    /**
     * Calculate management reports by running the sqls.
     *
     * @param array  $condition Conditions from the report filter
     * @param string $file      Sql file to load
     */
    private function _calculateManagementReport($conditions, $file)
    {
        // Configure::write('debug', 1);
        ini_set('memory_limit', '512M');
        $dataSource = ConnectionManager::getDataSource('default');
        $reports = $this->_readManagementReportConfig($file);
        $email = 0;
        foreach ($reports as $key => $config) {
            if ($config['isDisabled']) {
                continue;
            }
            $sql = String::insert($config['sql'], $conditions);
            $reports[$key]['run'] = $sql;
            $reports[$key]['result'] = $dataSource->query($sql);
            if ($file == 'management_reports.sql' && empty($reports[$key]['result'])) {
                $log = $dataSource->getLog(false, false);
                $addresses = array('phpdevelop@toltech.nl');
                $error = "Problem in ECD management report. Maybe there's bad input data that makes a bad query, but it could also be that the temporary table regenboog-live.tmp_visits is missing. This happened before, on low disk space.\n\n".$sql;
                $this->log($error);
                if (!$email) {
                    $this->_genericSendEmail(array(
                                'to' => $addresses,
                                'content' => $error,
                                'template' => 'blank',
                                'subject' => 'ECD error',
                                ));
                    ++$email;
                }
            }
            if (isset($mail) && $mail) {
                $this->log($reports);
            }
        }

        return $reports;
    }

    public function ladis()
    {
        ini_set('memory_limit', '512M');
        if (!$this->data) {
            $this->data['options']['excel'] = 1;
        }
        /*
        //dates
        $date_from = null;
        $date_to = null;
        $this->_prepare_dates($date_from, $date_to);
        $this->set(compact('date_from', 'date_to'));
        */
    }

    /**
     * Generic Management report, SQL file is passed as an argument.
     * Conditions are not really used so far, when necessary we have to decide if we pass them as an encoded array in the AJAX call, or posted, or whatever.
     */
    public function ajaxReport($config = 'ladis_report', $conditions = null)
    {
        $reports = $this->_calculateManagementReport($conditions, $config.'.sql');

        $this->autoLayout = false;

        $this->set(compact('reports'));
        if (!empty($this->data['options']['excel'])) {
            $this->layout = false;
            $file = 'management_report.xls';
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=\"$file\";");
            header('Content-Transfer-Encoding: binary');
            $this->render('management_excel');
        } else {
            $this->render('ajax_management');
        }
    }

    /**
     * Reads the management report config from the management_reports.sql
     * file and parses them.
     *
     * @param string $file Sql file to load
     */
    private function _readManagementReportConfig($file)
    {
        $reports = [];
        $config = preg_split('/-- START.*\n/m', file_get_contents(APP.'/config/'.$file));
        foreach ($config as $report) {
            $report = trim($report);
            if (!$report) {
                continue;
            }
            preg_match('/-- HEAD:\s*(.*)\n/m', $report, $matches);
            if (empty($matches[1])) {
                debug('Head not found:');
                debug($report);
            }
            $head = $matches[1];

            preg_match('/-- FIELDS:\s*(.*)\n/m', $report, $matches);
            if (empty($matches[1])) {
                debug('Fields not found:');
                debug($report);
            }
            $fields = $matches[1];
            $fields = preg_split("/[\s]*[;][\s]*/", $fields);
            foreach ($fields as $key => $field) {
                $fields[$key] = preg_split("/[\s]*[-][\s]*/", $field, 2);
            }

            $isArray = preg_match('/-- ARRAY/m', $report, $matches);
            $isDisabled = preg_match('/-- DISABLE/m', $report, $matches);
            $hasSummary = preg_match('/-- SUMMARY/m', $report, $matches);

            preg_match_all('/^([^-].*)\n/m', $report, $matches);
            $sql = implode("\n", $matches[1]);

            $reports[] = array(
                'head' => $head,
                'fields' => $fields,
                'isArray' => $isArray,
                'isDisabled' => $isDisabled,
                'hasSummary' => $hasSummary,
                'sql' => $sql,
            );
        }

        return $reports;
    }

    public function geenHulpverlenerscontact()
    {
        $this->Locatie->recursive = -1;
        $locations = $this->Locatie->find('list',
            array('fields' => array('Locatie.id', 'Locatie.naam'))
        );
        $this->set('locations',  $locations);
    }

    public function ajaxGeenHulpverlenerscontact()
    {
        $conditions = [];

        $options = $this->data['options'];

        //dates from quarters and years
        $dateFrom = date('Y-m-d', mktime(
            0, 0, 0, ($options['quarter'] - 1) * 3 + 1, 1, $options['year'])
        );
        $dateTo = date('Y-m-d', mktime(
            0, 0, 0, ($options['quarter']) * 3 + 1, 1, $options['year'])
        );
        if ($options['geslacht_id'] == 0) {
            $conditions['gender'] = '1, 2';
        } else {
            $conditions['gender'] = (int) $options['geslacht_id'];
        }

        $conditions['from'] = "'".$dateFrom."'";
        $conditions['until'] = "'".$dateTo."'";
        $conditions['location'] = (int) $options['location'];

        if (empty($this->data['options']['excel'])) {
            $this->SqlReport->ajaxDisplay(
                $conditions,
                'geen_hulpverlenerscontact'
            );
        } else {
            $this->SqlReport->excelDisplay(
                $conditions,
                'geen_hulpverlenerscontact',
                'geen_hulpverlenerscontact'
            );
        }
    }

    /* We may need to use something like this to dump to output records one by one, instead of loading them all in memory first. */

    /*

    function fetchAll($sql, $cache = true, $modelName = null) {

        // $this is $dataSource = ConnectionManager::getDataSource('default');
        if ($cache && isset($this->_queryCache[$sql])) {
            if (preg_match('/^\s*select/i', $sql)) {
                return $this->_queryCache[$sql];
            }
        }

        if ($this->execute($sql)) {
            $out = [];

            $first = $this->fetchRow();
            if ($first != null) {
                $out[] = $first;
            }
            while ($this->hasResult() && $item = $this->fetchResult()) {
                $this->fetchVirtualField($item);
                $out[] = $item;
            }

            if ($cache) {
                if (strpos(trim(strtolower($sql)), 'select') !== false) {
                    $this->_queryCache[$sql] = $out;
                }
            }
            if (empty($out) && is_bool($this->_result)) {
                return $this->_result;
            }
            return $out;
        } else {
            return false;
        }
    }
    */
}
