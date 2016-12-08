<?php

use IzBundle\Form\IzRapportageType;
use IzBundle\Entity\IzKoppeling;
use IzBundle\Entity\IzHulpvraag;
use AppBundle\Report\Table;

class IzRapportagesController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

//     public $components = array('ComponentLoader');

    public $report_select = array(
        'A1' => 'A1: Nieuwe koppelingen',
        'A2' => 'A2: Namenlijst nieuwe koppelingen',
        'B1' => 'B1: Afgesloten koppelingen',
        'B2' => 'B2: Namenlijst afgesloten koppelingen',
        'C1' => 'C1: Succesvolle koppelingen ',
        'C2' => 'C2: Namenlijst succesvolle koppelingen',
        'F1' => 'F1: Nw koppelingen unieke vrijwilligers',
        'F2' => 'F2: Nw koppelingen namen unieke vrijwilligers',
        'J1' => 'J1: Nw koppelingen unieke deelnemers',
        'J2' => 'J2: Nw koppelingen namen unieke deelnemers',
        'K1' => 'K1: Nw deelnemers zonder intake',
        'K2' => 'K2: Nw deelnemers zonder intake namenlijst',
        'L1' => 'L1: Nw deelnemers zonder vraag',
        'L2' => 'L2: Namenlijst deelnemers zonder vraag',
        'Z1' => 'Z1: Per werkgebied',
        'Z2' => 'Z2: Per project',
        'Z3' => 'Z3: Deelnemers per verwijzing',
        'Z4' => 'Z4: Vrijwilligers per verwijzing',
        'Z5' => 'Z5: Vrijwilligers per project',
    );

    public function index()
    {
        $form = $this->createForm(IzRapportageType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $reportName = $form->get('rapport')->getData();
            $method = 'report_'.$reportName;
            if (method_exists($this, $method)) {
                $this->{$method}($form->get('startdatum')->getData(), $form->get('einddatum')->getData());
            }
        }

        $this->set('form', $form->createView());
    }

    public function ajax_report_html()
    {
        // 		$this->loadModel('IzDeelnemer');
// 		$this->loadModel('IzKoppeling');
// 		$this->loadModel('IzOntstaanContact');
// 		$this->loadModel('IzViaPersoon');
// 		$this->autoLayout = false;
// 		$mainlabel = '';

// 		$werkgebieden = $this->IzDeelnemer->get_werkgebieden();
// 		$werkgebieden['Totaal'] = 'Totaal';

// 		$projects =  $this->IzDeelnemer->IzDeelnemersIzProject->IzProject->projectLists();
// 		$projects['Onbekend'] = 'Onbekend';
// 		$projects['Totaal'] = 'Totaal';

// 		$date_fields = array('koppeling_einddatum', 'koppeling_startdatum', 'datum_aanmelding', 'intake_datum');

// 		$contacts = $this->IzOntstaanContact->ontstaanContactList();
// 		$binnengekomen = $this->IzViaPersoon->viaPersoon();

        $startDate = $this->data['Query']['from'];
        $endDate = $this->data['Query']['until'];
        $queryName = $this->data['Query']['name'];

        if (isset($this->report_select[$queryName])) {
            $title = $this->report_select[$queryName];
        } else {
            foreach ($this->report_select as $group => $reports) {
                if (is_array($reports)) {
                    foreach ($reports as $key => $value) {
                        if ($queryName === $key) {
                            $title = $value;
                            $this->entity = str_replace(['Koppelingen'], ['IzKoppeling'], $group);
                            break 2;
                        }
                    }
                }
            }
        }

// 		switch ($queryName) {
// 			case 'A1':
// 				$labels = $werkgebieden;
// 				$mainlabel = 'Project';
// 				$report = $this->IzDeelnemer->A1_new_per_project_per_werkgebied($startDate, $endDate, $werkgebieden);
// 				break;
// 			case 'A2':
// 				$labels = array('Werkgebied' => 'Werkgebied', 'Klant' => 'Klant', 'Vrijwilliger' => 'Vrijwilliger', 'koppeling_startdatum' => 'Koppeling Startdatum', 'koppeling_einddatum' => 'Koppeling Einddatum');
// 				$mainlabel = 'Project';
// 				$report = $this->IzDeelnemer->A2_new_per_project_per_werkgebied_totaal($startDate, $endDate, $labels);
// 				break;
// 			case 'B1':
// 				$labels = $werkgebieden;
// 				$report = $this->IzDeelnemer->B1_stopped_per_project_per_werkgebied($startDate, $endDate, $werkgebieden);
// 				break;
// 			case 'B2':
// 				$labels = array('Werkgebied' => 'Werkgebied', 'Klant' => 'Klant', 'Vrijwilliger' => 'Vrijwilliger',  'koppeling_startdatum' => 'Koppeling Startdatum', 'koppeling_einddatum' => 'Koppeling Einddatum');
// 				$mainlabel = 'Project';
// 				$report = $this->IzDeelnemer->B2_stopped_per_project_per_werkgebied_totaal($startDate, $endDate, $labels);
// 				break;
// 			case 'C1':
// 				$labels = $werkgebieden;
// 				$mainlabel = 'Project';
// 				$report = $this->IzDeelnemer->C1_geslaagd_per_project_per_werkgebied($startDate, $endDate, $werkgebieden);
// 				break;
// 			case 'C2':
// 				$labels = array('Werkgebied' => 'Werkgebied', 'Klant' => 'Klant', 'Vrijwilliger' => 'Vrijwilliger', 'koppeling_startdatum' => 'Koppeling Startdatum',  'koppeling_einddatum' => 'Koppeling Einddatum');
// 				$mainlabel = 'Project';
// 				$report = $this->IzDeelnemer->C2_geslaagd_per_project_per_werkgebied_totaal($startDate, $endDate, $labels);
// 				break;
// 			case 'F1':
// 				$labels = $werkgebieden;
// 				$mainlabel = 'Project';
// 				$report = $this->IzDeelnemer->F1_nieuwe_vrijwilligers_per_project_per_werkgebied($startDate, $endDate, $werkgebieden);
// 				break;
// 			case 'F2':
// 				$labels = array('Werkgebied' => 'Werkgebied', 'Vrijwilliger' => 'Vrijwilliger', 'koppeling_startdatum' => 'Koppeling Startdatum',  'koppeling_einddatum' => 'Koppeling Einddatum');
// 				$mainlabel = 'Project';
// 				$report = $this->IzDeelnemer->F2_namen_nieuwe_vrijwilligers_per_project_per_werkgebied($startDate, $endDate, $labels);
// 				break;
// 			case 'J1':
// 				$labels = $werkgebieden;
// 				$mainlabel = 'Project';
// 				$report = $this->IzDeelnemer->J1_nieuwe_deelnemers_per_project_per_werkgebied($startDate, $endDate, $werkgebieden);
// 				break;
// 			case 'J2':
// 				$labels = array('Werkgebied' => 'Werkgebied', 'Klant' => 'Klant', 'koppeling_startdatum' => 'Koppeling Startdatum',  'koppeling_einddatum' => 'Koppeling Einddatum');
// 				$mainlabel = 'Project';
// 				$report = $this->IzDeelnemer->J2_namen_nieuwe_deelnemers_per_project_per_werkgebied($startDate, $endDate, $labels);
// 				break;
// 			case 'K1':
// 				$labels = array('totaal' => 'Totaal');
// 				$mainlabel = 'Werkgebied';
// 				$report = $this->IzDeelnemer->K1_nieuwe_deelnemers_per_per_werkgebied_zonder_intake($startDate, $endDate, $labels);
// 				break;
// 			case 'K2':
// 				$labels = array('Klant' => 'Klant', 'datum_aanmelding' => 'Datum aanmelding');
// 				$mainlabel = 'Werkgebied';
// 				$report = $this->IzDeelnemer->K2_namen_nieuwe_deelnemers_per_per_werkgebied_zonder_intake($startDate, $endDate, $labels);
// 				break;
// 			case 'L1':
// 				$labels = array('totaal' => 'Totaal');
// 				$mainlabel = 'Werkgebied';
// 				$report = $this->IzDeelnemer->L1_nieuwe_deelnemers_per_per_werkgebied_zonder_aanbod($startDate, $endDate, $labels);
// 				break;
// 			case 'L2':
// 				$labels = array('Klant' => 'Klant', 'datum_aanmelding' => 'Datum aanmelding', 'intake_datum' => 'Datum intake', 'medewerker' => 'Coordinator Intake');
// 				$mainlabel = 'Werkgebied';
// 				$report = $this->IzDeelnemer->L2_namen_nieuwe_deelnemers_per_per_werkgebied_zonder_aanbod($startDate, $endDate, $labels);
// 				break;
// 			case 'Z1':
// 				$labels = $werkgebieden;
// 				$report[] = $this->IzDeelnemer->nieuwe_koppelingen_report_html($startDate, $endDate, $werkgebieden);
// 				$report[] = $this->IzDeelnemer->active_klanten_report_html($startDate, $endDate, $werkgebieden);
// 				$report[] = $this->IzDeelnemer->active_klanten_op_einddatum_report_html($startDate, $endDate, $werkgebieden);
// 				$report[] = $this->IzDeelnemer->wachtlijst_klanten_report_html($startDate, $endDate, $werkgebieden);
// 				$report[] = $this->IzDeelnemer->wachtlijst_vrijwilligers_report_html($startDate, $endDate, $werkgebieden);
// 				$report[] = $this->IzDeelnemer->gemiddelde_wachttijd_klant_report_html($startDate, $endDate, $werkgebieden);
// 				break;
// 			case 'Z2':
// 				$labels = $projects;
// 				$report[] = $this->IzDeelnemer->nieuwe_koppelingen_report_html($startDate, $endDate, $projects, true);
// 				$report[] = $this->IzDeelnemer->active_klanten_report_html($startDate, $endDate, $projects, true);
// 				$report[] = $this->IzDeelnemer->active_klanten_op_einddatum_report_html($startDate, $endDate, $projects, true);
// 				$report[] = $this->IzDeelnemer->wachtlijst_klanten_report_html($startDate, $endDate, $projects, true);
// 				$report[] = $this->IzDeelnemer->wachtlijst_vrijwilligers_report_html($startDate, $endDate, $projects, true);
// 				$report[] = $this->IzDeelnemer->gemiddelde_wachttijd_klant_report_html($startDate, $endDate, $projects, true);
// 				break;
// 			case 'Z3':
// 				$labels = array('Totaal' => 'Aantal nieuwe (op basis datum aanmelding) deelnemers per verwijzer');
// 				$report = $this->IzDeelnemer->aanvullend_contact_html($startDate, $endDate, $contacts);
// 				break;
// 			case 'Z4':
// 				$labels = array('Totaal' => 'Aantal nieuwe (op basis datum aanmelding) vrijwilligers per verwijzer');
// 				$report = $this->IzDeelnemer->aanvullend_binnengekomen_html($startDate, $endDate, $binnengekomen);
// 				break;
// 			case 'Z5':
// 				$labels = array('Totaal' => 'Aantal nieuwe vrijwilligers (op basis datum aanmelding) per project');
// 				$report = $this->IzDeelnemer->aanvullend_aanmelding_html($startDate, $endDate, $projects);
// 				break;
// 		}

// 		$this->set(compact('date_fields', 'report', 'title', 'contacts', 'labels', 'mainlabel', 'binnengekomen', 'startDate', 'endDate'));

// 		if (! empty($this->data['options']['excel'])) {
// 			$this->layout = false;

// 			if (isset($report)) {
// 				// render template...
// 				$file = "iz_deelnemers_report_{$startDate}_{$endDate}.xls";
// 				header('Content-type: application/vnd.ms-excel');
// 				header("Content-Disposition: attachment; filename=\"$file\";");
// 				header("Content-Transfer-Encoding: binary");
// 				return $this->render('report_excel');
// 			} else {
// 				// ...or use dedicated method
// 				$file = "iz_deelnemers_report_{$startDate}_{$endDate}.csv";
// 				header('Content-type: text/csv');
// 				header("Content-Disposition: attachment; filename=\"$file\";");
// 				return $this->{'report_'.$reportName}(new \DateTime($startDate), new \DateTime($endDate), 'csv');
// 			}
// 		}

// 		if (isset($report)) {
// 			// render template...
// 			return $this->render('report_html');
// 		} else {
            // ...or use dedicated method
            $this->{'report_'.$queryName}(new \DateTime($startDate), new \DateTime($endDate));
// 		}
    }

    private function report_koppelingen_totaal(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $repository = $this->getEntityManager()->getRepository(IzHulpvraag::class);

        $beginstand = $repository->count('beginstand', $startDate, $endDate);
        $gestart = $repository->count('gestart', $startDate, $endDate);
        $afgesloten = $repository->count('afgesloten', $startDate, $endDate);
        $succesvolAfgesloten = $repository->count('succesvol_afgesloten', $startDate, $endDate);
        $eindstand = $repository->count('eindstand', $startDate, $endDate);

        $beginstandTable = new Table($beginstand, null, null, 'aantal', 'beginstand');
        $beginstandTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $gestartTable = new Table($gestart, null, null, 'aantal', 'gestart');
        $gestartTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $afgeslotenTable = new Table($afgesloten, null, null, 'aantal', 'afgesloten');
        $afgeslotenTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $succesvolAfgeslotenTable = new Table($succesvolAfgesloten, null, null, 'aantal', 'succesvol_afgesloten');
        $succesvolAfgeslotenTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $eindstandTable = new Table($eindstand, null, null, 'aantal', 'eindstand');
        $eindstandTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $title = 'Koppelingen totaal';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Aantal koppelingen',
                'data' => $beginstandTable->render(),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Aantal koppelingen',
                'data' => $gestartTable->render(),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal koppelingen',
                'data' => $afgeslotenTable->render(),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => 'Aantal koppelingen',
                'data' => $succesvolAfgeslotenTable->render(),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Aantal koppelingen',
                'data' => $eindstandTable->render(),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_koppelingen_per_project(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(IzHulpvraag::class);

        $beginstand = $repository->countByProject('beginstand', $startDate, $endDate);
        $gestart = $repository->countByProject('gestart', $startDate, $endDate);
        $afgesloten = $repository->countByProject('afgesloten', $startDate, $endDate);
        $succesvolAfgesloten = $repository->countByProject('succesvol_afgesloten', $startDate, $endDate);
        $eindstand = $repository->countByProject('eindstand', $startDate, $endDate);

        $beginstandTable = new Table($beginstand, null, 'project', 'aantal', 'beginstand');
        $beginstandTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $gestartTable = new Table($gestart, null, 'project', 'aantal', 'gestart');
        $gestartTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $afgeslotenTable = new Table($afgesloten, null, 'project', 'aantal', 'afgesloten');
        $afgeslotenTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $succesvolAfgeslotenTable = new Table($succesvolAfgesloten, null, 'project', 'aantal', 'succesvol_afgesloten');
        $succesvolAfgeslotenTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $eindstandTable = new Table($eindstand, null, 'project', 'aantal', 'eindstand');
        $eindstandTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $title = 'Koppelingen per project';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Aantal koppelingen',
                'yDescription' => 'Project',
                'data' => $beginstandTable->render(),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Aantal koppelingen',
                'yDescription' => 'Project',
                'data' => $gestartTable->render(),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal koppelingen',
                'yDescription' => 'Project',
                'data' => $afgeslotenTable->render(),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => 'Aantal koppelingen',
                'yDescription' => 'Project',
                'data' => $succesvolAfgeslotenTable->render(),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Aantal koppelingen',
                'yDescription' => 'Project',
                'data' => $eindstandTable->render(),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_koppelingen_per_stadsdeel(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
        ) {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(IzHulpvraag::class);

        $beginstand = $repository->countByStadsdeel('beginstand', $startDate, $endDate);
        $gestart = $repository->countByStadsdeel('gestart', $startDate, $endDate);
        $afgesloten = $repository->countByStadsdeel('afgesloten', $startDate, $endDate);
        $succesvolAfgesloten = $repository->countByStadsdeel('succesvol_afgesloten', $startDate, $endDate);
        $eindstand = $repository->countByStadsdeel('eindstand', $startDate, $endDate);

        $beginstandTable = new Table($beginstand, null, 'stadsdeel', 'aantal', 'beginstand');
        $beginstandTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ;

        $gestartTable = new Table($gestart, null, 'stadsdeel', 'aantal', 'gestart');
        $gestartTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ;

        $afgeslotenTable = new Table($afgesloten, null, 'stadsdeel', 'aantal', 'afgesloten');
        $afgeslotenTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ;

        $succesvolAfgeslotenTable = new Table($succesvolAfgesloten, null, 'stadsdeel', 'aantal', 'succesvol_afgesloten');
        $succesvolAfgeslotenTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ;

        $eindstandTable = new Table($eindstand, null, 'stadsdeel', 'aantal', 'eindstand');
        $eindstandTable
            ->setController('iz_koppelingen')
            ->setAction('index')
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ;

        $title = 'Koppelingen per stadsdeel';
        $reports = array(
                array(
                    'title' => 'Beginstand',
                    'xDescription' => 'Aantal koppelingen',
                    'yDescription' => 'Stadsdeel',
                    'data' => $beginstandTable->render(),
                ),
                array(
                    'title' => 'Gestart',
                    'xDescription' => 'Aantal koppelingen',
                    'yDescription' => 'Stadsdeel',
                    'data' => $gestartTable->render(),
                ),
                array(
                    'title' => 'Afgesloten',
                    'xDescription' => 'Aantal koppelingen',
                    'yDescription' => 'Stadsdeel',
                    'data' => $afgeslotenTable->render(),
                ),
                array(
                    'title' => 'Succesvol afgesloten',
                    'xDescription' => 'Aantal koppelingen',
                    'yDescription' => 'Stadsdeel',
                    'data' => $succesvolAfgeslotenTable->render(),
                ),
                array(
                    'title' => 'Eindstand',
                    'xDescription' => 'Aantal koppelingen',
                    'yDescription' => 'Stadsdeel',
                    'data' => $eindstandTable->render(),
                ),
            );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_koppelingen_postcodegebied(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $title = 'Koppelingen per postcodegebied';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Aantal koppelingen',
                'yDescription' => 'Postcodegebied',
                'data' => $this->IzKoppeling->count_per_postcodegebied(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Aantal koppelingen',
                'yDescription' => 'Postcodegebied',
                'data' => $this->IzKoppeling->count_per_postcodegebied(
                    'gestart', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal koppelingen',
                'yDescription' => 'Postcodegebied',
                'data' => $this->IzKoppeling->count_per_postcodegebied(
                    'afgesloten', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Succesvol afgesloten',
// 				'xDescription' => 'Aantal koppelingen',
// 				'yDescription' => 'Postcodegebied',
// 				'data' => $this->IzKoppeling->count_per_postcodegebied(
// 					'succesvol_afgesloten', $startDate, $endDate
// 				),
// 			),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Aantal koppelingen',
                'yDescription' => 'Postcodegebied',
                'data' => $this->IzKoppeling->count_per_postcodegebied(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));

        return $this->render('pivot_tables.'.$format);
    }

    private function report_koppelingen_project_stadsdeel(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $title = 'Koppelingen per project en stadsdeel';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Stadsdeel',
                'yDescription' => 'Project',
                'data' => $this->IzKoppeling->count_per_project_stadsdeel_beginstand(
                    $startDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Stadsdeel',
                'yDescription' => 'Project',
                'data' => $this->IzKoppeling->count_per_project_stadsdeel_gestart(
                    $startDate,
                    $endDate
                ),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Stadsdeel',
                'yDescription' => 'Project',
                'data' => $this->IzKoppeling->count_per_project_stadsdeel_afgesloten(
                    $startDate,
                    $endDate
                ),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => 'Stadsdeel',
                'yDescription' => 'Project',
                'data' => $this->IzKoppeling->count_per_project_stadsdeel_succesvol_afgesloten(
                    $startDate,
                    $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Stadsdeel',
                'yDescription' => 'Project',
                'data' => $this->IzKoppeling->count_per_project_stadsdeel_eindstand(
                    $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));

        return $this->render('pivot_tables.'.$format);
    }

    private function report_koppelingen_project_postcodegebied(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $title = 'Koppelingen per project en postcodegebied';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Postcodegebied',
                'yDescription' => 'Project',
                'data' => $this->IzKoppeling->count_per_project_postcodegebied_beginstand(
                    $startDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Postcodegebied',
                'yDescription' => 'Project',
                'data' => $this->IzKoppeling->count_per_project_postcodegebied_gestart(
                    $startDate,
                    $endDate
                ),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Postcodegebied',
                'yDescription' => 'Project',
                'data' => $this->IzKoppeling->count_per_project_postcodegebied_afgesloten(
                    $startDate,
                    $endDate
                ),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => 'Postcodegebied',
                'yDescription' => 'Project',
                'data' => $this->IzKoppeling->count_per_project_postcodegebied_succesvol_afgesloten(
                    $startDate,
                    $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Postcodegebied',
                'yDescription' => 'Project',
                'data' => $this->IzKoppeling->count_per_project_postcodegebied_eindstand(
                    $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));

        return $this->render('pivot_tables.'.$format);
    }

    private function report_vrijwilligers_aanmeldingen(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzVrijwilliger');
        $title = 'Vrijwilligers | Aanmeldingen';
        $reports = array(
            array(
                'title' => 'Aanmeldingen',
                'xDescription' => 'Aanmeldingen en daaruit volgende intakes, hulpaanbiedingen en koppelingen binnen datumbereik.',
                'yDescription' => '',
                'data' => $this->IzVrijwilliger->count_aanmeldingen(
                    $startDate,
                    $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_vrijwilligers_aanmeldingen_coordinator(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzVrijwilliger');
        $title = 'Vrijwilligers | Aanmeldingen per coördinator';
        $medewerkers = $this->getMedewerkers();
        $reports = array(
            array(
                'title' => 'Aanmeldingen per coördinator',
                'xDescription' => 'Aanmeldingen en daaruit volgende intakes, hulpaanbiedingen en koppelingen binnen datumbereik.',
                'yDescription' => 'Medewerkers uit basisdossier vrijwilliger (aanmeldingen), van IZ-intake (intakes), coördinatoren hulpaanbod (hulpaanbiedingen en koppelingen)',
                'yLookupCollection' => $medewerkers,
                'data' => $this->IzVrijwilliger->count_aanmeldingen_per_coordinator(
                    $startDate,
                    $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_vrijwilligers_totaal(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzVrijwilliger');
        $title = 'Vrijwilligers';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => '',
                'yDescription' => '',
                'data' => $this->IzVrijwilliger->count(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => '',
                'yDescription' => '',
                'data' => $this->IzVrijwilliger->count(
                    'gestart', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Nieuw Gestart',
// 				'xDescription' => '',
// 				'yDescription' => '',
// 				'data' => $this->IzVrijwilliger->count_nieuw(
// 					'nieuw_gestart', $startDate, $endDate
// 				),
// 			),
            array(
                'title' => 'Afgesloten',
                'xDescription' => '',
                'yDescription' => '',
                'data' => $this->IzVrijwilliger->count(
                    'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => '',
                'yDescription' => '',
                'data' => $this->IzVrijwilliger->count(
                    'succesvol_afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => '',
                'yDescription' => '',
                'data' => $this->IzVrijwilliger->count(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_vrijwilligers_coordinator(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzVrijwilliger');
        $title = 'Vrijwilligers per coördinator';
        $medewerkers = $this->getMedewerkers();
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $medewerkers,
                'data' => $this->IzVrijwilliger->count_per_coordinator(
                    'beginstand',
                    $startDate,
                    $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $medewerkers,
                'data' => $this->IzVrijwilliger->count_per_coordinator(
                    'gestart', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Nieuw Gestart',
// 				'xDescription' => '',
// 				'yDescription' => '',
// 				'yLookupCollection' => $medewerkers,
// 				'data' => $this->IzVrijwilliger->count_per_coordinator_nieuw_gestart(
// 					$startDate,
// 					$endDate
// 				),
// 			),
            array(
                'title' => 'Afgesloten',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $medewerkers,
                'data' => $this->IzVrijwilliger->count_per_coordinator(
                    'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $medewerkers,
                'data' => $this->IzVrijwilliger->count_per_coordinator(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_vrijwilligers_project_stadsdeel(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzVrijwilliger');
        $title = 'Vrijwilligers per project en stadsdeel';
        $projecten = $this->getIzProjecten();
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project_stadsdeel(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project_stadsdeel(
                    'gestart', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Nieuw Gestart',
// 				'xDescription' => '',
// 				'yDescription' => '',
// 				'data' => $this->IzVrijwilliger->count_per_project_stadsdeel(
// 				    'nieuw_gestart', $startDate, $endDate
// 				),
// 			),
            array(
                'title' => 'Afgesloten',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project_stadsdeel(
                    'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project_stadsdeel(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_vrijwilligers_stadsdeel(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzVrijwilliger');

        $title = 'Vrijwilligers per stadsdeel';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Stadsdeel op basis van woonadres vrijwilliger',
                'data' => $this->IzVrijwilliger->count_per_stadsdeel(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Stadsdeel op basis van woonadres vrijwilliger',
                'data' => $this->IzVrijwilliger->count_per_stadsdeel(
                    'gestart', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Nieuw gestart',
// 				'xDescription' => 'Aantal vrijwilligers',
// 				'yDescription' => 'Stadsdeel op basis van woonadres vrijwilliger',
// 				'data' => $this->IzVrijwilliger->count_per_stadsdeel(
// 				    'nieuw_gestart', $startDate, $endDate
// 				),
// 			),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Stadsdeel op basis van woonadres vrijwilliger',
                'data' => $this->IzVrijwilliger->count_per_stadsdeel(
                    'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Stadsdeel op basis van woonadres vrijwilliger',
                'data' => $this->IzVrijwilliger->count_per_stadsdeel(
                    'succesvol_afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Stadsdeel op basis van woonadres vrijwilliger',
                'data' => $this->IzVrijwilliger->count_per_stadsdeel(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_vrijwilligers_project(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzVrijwilliger');
        $title = 'Vrijwilligers per project';
        $projecten = $this->getIzProjecten();
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project(
                    'gestart', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Nieuw gestart',
// 				'xDescription' => 'Aantal vrijwilligers',
// 				'yDescription' => 'Project',
// 		         'yLookupCollection' => $projecten,
// 				'data' => $this->IzVrijwilliger->count_per_project(
// 				    'nieuw_gestart', $startDate, $endDate
// 				),
// 			),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project(
                    'afgesloten', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Succesvol afgesloten',
// 				'xDescription' => 'Aantal vrijwilligers',
// 				'yDescription' => 'Project',
// 				'yLookupCollection' => $projecten,
// 				'data' => $this->IzVrijwilliger->count_per_project(
// 					'succesvol_afgesloten', $startDate, $endDate
// 				),
// 			),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_vrijwilligers_postcodegebied(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzVrijwilliger');

        $title = 'Vrijwilligers per postcodegebied';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
                'data' => $this->IzVrijwilliger->count_per_postcodegebied(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
                'data' => $this->IzVrijwilliger->count_per_postcodegebied(
                    'gestart', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Nieuw gestart',
// 				'xDescription' => 'Aantal vrijwilligers',
// 				'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
// 				'data' => $this->IzVrijwilliger->count_per_postcodegebied(
// 					'nieuw_gestart', $startDate, $endDate
// 				),
// 			),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
                'data' => $this->IzVrijwilliger->count_per_postcodegebied(
                    'afgesloten', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Succesvol afgesloten',
// 				'xDescription' => 'Aantal vrijwilligers',
// 				'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
// 				'data' => $this->IzVrijwilliger->count_per_postcodegebied(
// 					'succesvol_afgesloten', $startDate, $endDate
// 				),
// 			),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Aantal vrijwilligers',
                'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
                'data' => $this->IzVrijwilliger->count_per_postcodegebied(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_vrijwilligers_project_postcodegebied(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzVrijwilliger');
        $title = 'Vrijwilligers met koppeling per project en postcodegebied';
        $projecten = $this->getIzProjecten();
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project_postcodegebied(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project_postcodegebied(
                    'gestart', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Nieuw gestart',
// 				'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
// 				'yDescription' => 'Project',
// 				'data' => $this->IzVrijwilliger->count_per_project_postcodegebied_nieuw_gestart(
// 					'', $startDate, $endDate
// 				),
// 			),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project_postcodegebied(
                    'afgesloten', $startDate, $endDate
                ),
            ),
// 			array(
// 				'title' => 'Succesvol afgesloten',
// 				'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
// 				'yDescription' => 'Project',
// 				'data' => $this->IzVrijwilliger->count_per_project_postcodegebied(
// 					'succesvol_afgesloten', $startDate, $endDate
// 				),
// 			),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzVrijwilliger->count_per_project_postcodegebied(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_klanten_aanmeldingen(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzKlant');
        $title = 'Klanten | Aanmeldingen';
        $reports = array(
            array(
                'title' => 'Aanmeldingen',
                'xDescription' => 'Aanmeldingen en daaruit volgende intakes, hulpvragen en koppelingen binnen datumbereik.',
                'yDescription' => '',
                'data' => $this->IzKlant->count_aanmeldingen(
                    $startDate,
                    $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_klanten_aanmeldingen_coordinator(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzKlant');
        $title = 'Klanten | Aanmeldingen per coördinator';
        $medewerkers = $this->getMedewerkers();
        $reports = array(
            array(
                'title' => 'Aanmeldingen per coördinator',
                'xDescription' => 'Aanmeldingen en daaruit volgende intakes, hulpvragen en koppelingen. Aanmeldingen binnen het datumbereik. Intakes, hulpvragen en koppelingen kunnen buiten datumbereik liggen.',
                'yDescription' => 'Medewerkers uit basisdossier vrijwilliger (aanmeldingen), van IZ-intake (intakes), coördinatoren hulpvraag (hulpvragen en koppelingen)',
                'yLookupCollection' => $medewerkers,
                'data' => $this->IzKlant->count_aanmeldingen_per_coordinator(
                    $startDate,
                    $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_klanten_totaal(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzKlant');
        $title = 'Klanten';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => '',
                'yDescription' => '',
                'data' => $this->IzKlant->count(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => '',
                'yDescription' => '',
                'data' => $this->IzKlant->count(
                    'gestart', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => '',
                'yDescription' => '',
                'data' => $this->IzKlant->count(
                    'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => '',
                'yDescription' => '',
                'data' => $this->IzKlant->count(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_klanten_coordinator(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzKlant');
        $title = 'Klanten per coördinator';
        $medewerkers = $this->getMedewerkers();
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $medewerkers,
                'data' => $this->IzKlant->count_per_coordinator(
                     'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $medewerkers,
                'data' => $this->IzKlant->count_per_coordinator(
                    'gestart', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $medewerkers,
                'data' => $this->IzKlant->count_per_coordinator(
                    'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => '',
                'yDescription' => '',
                'yLookupCollection' => $medewerkers,
                'data' => $this->IzKlant->count_per_coordinator(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_klanten_project(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzKlant');
        $title = 'Klanten per project';
        $projecten = $this->getIzProjecten();
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project(
                    'gestart', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project(
                  'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project(
                  'succesvol_afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project(
                  'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_klanten_stadsdeel(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzKlant');

        $title = 'Klanten per stadsdeel';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Stadsdeel op basis van woonadres klant',
                'data' => $this->IzKlant->count_per_stadsdeel(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Stadsdeel op basis van woonadres klant',
                'data' => $this->IzKlant->count_per_stadsdeel(
                    'gestart', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Stadsdeel op basis van woonadres klant',
                'data' => $this->IzKlant->count_per_stadsdeel(
                    'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Stadsdeel op basis van woonadres klant',
                'data' => $this->IzKlant->count_per_stadsdeel(
                    'succesvol_afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Stadsdeel op basis van woonadres klant',
                'data' => $this->IzKlant->count_per_stadsdeel(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_klanten_postcodegebied(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzKlant');

        $title = 'Klanten met koppeling per postcodegebied';
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Postcodegebied op basis van woonadres klant',
                'data' => $this->IzKlant->count_per_postcodegebied(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Postcodegebied op basis van woonadres klant',
                'data' => $this->IzKlant->count_per_postcodegebied(
                    'gestart', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Postcodegebied op basis van woonadres klant',
                'data' => $this->IzKlant->count_per_postcodegebied(
                    'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Postcodegebied op basis van woonadres klant',
                'data' => $this->IzKlant->count_per_postcodegebied(
                    'succesvol_afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Aantal klanten',
                'yDescription' => 'Postcodegebied op basis van woonadres klant',
                'data' => $this->IzKlant->count_per_postcodegebied(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_klanten_project_stadsdeel(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzKlant');
        $title = 'Klanten per project en stadsdeel';
        $projecten = $this->getIzProjecten();
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Stadsdeel op basis van woonadres klant',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project_stadsdeel(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Stadsdeel op basis van woonadres klant',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project_stadsdeel(
                    'gestart', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Stadsdeel op basis van woonadres klant',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project_stadsdeel(
                    'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => 'Stadsdeel op basis van woonadres klant',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project_stadsdeel(
                    'succesvol_afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Stadsdeel op basis van woonadres klant',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project_stadsdeel(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function report_klanten_project_postcodegebied(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $this->loadModel('IzKlant');
        $title = 'Klanten met koppeling per project en postcodegebied';
        $projecten = $this->getIzProjecten();
        $reports = array(
            array(
                'title' => 'Beginstand',
                'xDescription' => 'Postcodegebied op basis van woonadres klant',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project_postcodegebied(
                    'beginstand', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Gestart',
                'xDescription' => 'Postcodegebied op basis van woonadres klant',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project_postcodegebied(
                    'gestart', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Afgesloten',
                'xDescription' => 'Postcodegebied op basis van woonadres klant',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project_postcodegebied(
                    'afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Succesvol afgesloten',
                'xDescription' => 'Postcodegebied op basis van woonadres klant',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project_postcodegebied(
                    'succesvol_afgesloten', $startDate, $endDate
                ),
            ),
            array(
                'title' => 'Eindstand',
                'xDescription' => 'Postcodegebied op basis van woonadres klant',
                'yDescription' => 'Project',
                'yLookupCollection' => $projecten,
                'data' => $this->IzKlant->count_per_project_postcodegebied(
                    'eindstand', $startDate, $endDate
                ),
            ),
        );

        $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    }

    private function check_persoon_model($persoon_model)
    {
        if ($persoon_model != 'Vrijwilliger' && $persoon_model != 'Klant') {
            echo 'Een foute invoer';
            exit;
        }

        if (!isset($this->{$persoon_model})) {
            $this->loadModel($persoon_model);
        }

        return $persoon_model;
    }

    private function setmetadata($id = null, $persoon_model = null, $foreign_key = null)
    {
        $iz_deelnemer = null;
        $is_afgesloten = false;

        if (!empty($id)) {
            $iz_deelnemer = $this->IzDeelnemer->getAllById($id);
            $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];
            $foreign_key = $iz_deelnemer['IzDeelnemer']['foreign_key'];

            if (!empty($iz_deelnemer['IzDeelnemer']['datumafsluiting']) &&
                    strtotime(date('Y-m-d')) >= strtotime($iz_deelnemer['IzDeelnemer']['datumafsluiting'])) {
                $is_afgesloten = true;
            }
        }

        $iz_intake = $this->IzDeelnemer->IzIntake->find('first', array(
                'conditions' => array('iz_deelnemer_id' => $id),
                'contain' => array(),

        ));

        $this->setMedewerkers();

        $persoon_model = $this->check_persoon_model($persoon_model);
        $persoon = $this->{$persoon_model}->getAllById($foreign_key);

        $geslachten = $this->{$persoon_model}->Geslacht->find('list');
        $landen = $this->{$persoon_model}->Geboorteland->find('list');

        $nationaliteiten = $this->{$persoon_model}->Nationaliteit->find('list');

        $werkgebieden = Configure::read('Werkgebieden');

        $persoon['IzDeelnemer']['IzDeelnemerDocument'] = array();

        if (!empty($iz_deelnemer['IzDeelnemerDocument'])) {
            $persoon['IzDeelnemer']['IzDeelnemerDocument'] = $iz_deelnemer['IzDeelnemerDocument'];
        }

        $this->set(compact(
                'id',
                'persoon',
                'persoon_model',
                'project_id',
                'foreign_key',
                'geslachten',
                'landen',
                'nationaliteiten',
                'medewerkers',
                'werkgebieden',
                'iz_intake',
                'iz_deelnemer',
                'is_afgesloten'
        ));
    }

    protected function formatPostedDate($key, $default = null)
    {
        $date = $this->data[$key];

        if (!empty($date['year']) && !empty($date['month']) && !empty($date['day'])) {
            $date = $date['year'].'-'.$date['month'].'-'.$date['day'];
        } else {
            $date = $default;
        }

        return $date;
    }

    private function getMedewerkers()
    {
        $this->loadModel('Medewerker');

        return $this->Medewerker->find('list', array(
            'joins' => array(
                array(
                    'table' => 'iz_koppelingen',
                    'alias' => 'IzHulpaanbod',
                    'type' => 'INNER',
                    'conditions' => array(
                        'IzHulpaanbod.medewerker_id = Medewerker.id',
                    ),
                ),
            ),
        ));
    }

    private function getIzProjecten()
    {
        $this->loadModel('IzProject');

        return $this->IzProject->find('list', array(
            'joins' => array(
                array(
                    'table' => 'iz_koppelingen',
                    'alias' => 'IzHulpaanbod',
                    'type' => 'INNER',
                    'conditions' => array(
                        'IzHulpaanbod.project_id = IzProject.id',
                    ),
                ),
            ),
        ));
    }
}
