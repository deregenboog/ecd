<?php

use IzBundle\Form\IzRapportageType;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Entity\IzKlant;

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

    public function index()
    {
        $form = $this->createForm(IzRapportageType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            // get reporting service
            $report = $this->container->get($form->get('rapport')->getData());

            // set options
            $report
                ->setStartDate($form->get('startdatum')->getData())
                ->setEndDate($form->get('einddatum')->getData())
            ;

            $this->set('title', $report->getTitle());
            $this->set('startDate', $report->getStartDate());
            $this->set('endDate', $report->getEndDate());
            $this->set('reports', $report->getReports());
        }

        $this->set('form', $form->createView());
    }

    // private function report_vrijwilligers_aanmeldingen(
    // \DateTime $startDate,
    // \DateTime $endDate,
    // $format = 'html'
    // ) {
    // $this->loadModel('IzVrijwilliger');
    // $title = 'Vrijwilligers | Aanmeldingen';
    // $reports = array(
    // array(
    // 'title' => 'Aanmeldingen',
    // 'xDescription' => 'Aanmeldingen en daaruit volgende intakes, hulpaanbiedingen en koppelingen binnen datumbereik.',
    // 'yDescription' => '',
    // 'data' => $this->IzVrijwilliger->count_aanmeldingen(
    // $startDate,
    // $endDate
    // ),
    // ),
    // );

    // $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    // }

    // private function report_vrijwilligers_aanmeldingen_coordinator(
    // \DateTime $startDate,
    // \DateTime $endDate,
    // $format = 'html'
    // ) {
    // $this->loadModel('IzVrijwilliger');
    // $title = 'Vrijwilligers | Aanmeldingen per coördinator';
    // $medewerkers = $this->getMedewerkers();
    // $reports = array(
    // array(
    // 'title' => 'Aanmeldingen per coördinator',
    // 'xDescription' => 'Aanmeldingen en daaruit volgende intakes, hulpaanbiedingen en koppelingen binnen datumbereik.',
    // 'yDescription' => 'Medewerkers uit basisdossier vrijwilliger (aanmeldingen), van IZ-intake (intakes), coördinatoren hulpaanbod (hulpaanbiedingen en koppelingen)',
    // 'yLookupCollection' => $medewerkers,
    // 'data' => $this->IzVrijwilliger->count_aanmeldingen_per_coordinator(
    // $startDate,
    // $endDate
    // ),
    // ),
    // );

    // $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    // }

    // private function report_vrijwilligers_coordinator(
    // \DateTime $startDate,
    // \DateTime $endDate,
    // $format = 'html'
    // ) {
    // $this->loadModel('IzVrijwilliger');
    // $title = 'Vrijwilligers per coördinator';
    // $medewerkers = $this->getMedewerkers();
    // $reports = array(
    // array(
    // 'title' => 'Beginstand',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $medewerkers,
    // 'data' => $this->IzVrijwilliger->count_per_coordinator(
    // 'beginstand',
    // $startDate,
    // $endDate
    // ),
    // ),
    // array(
    // 'title' => 'Gestart',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $medewerkers,
    // 'data' => $this->IzVrijwilliger->count_per_coordinator(
    // 'gestart', $startDate, $endDate
    // ),
    // ),
    // // array(
    // // 'title' => 'Nieuw Gestart',
    // // 'xDescription' => '',
    // // 'yDescription' => '',
    // // 'yLookupCollection' => $medewerkers,
    // // 'data' => $this->IzVrijwilliger->count_per_coordinator_nieuw_gestart(
    // // $startDate,
    // // $endDate
    // // ),
    // // ),
    // array(
    // 'title' => 'Afgesloten',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $medewerkers,
    // 'data' => $this->IzVrijwilliger->count_per_coordinator(
    // 'afgesloten', $startDate, $endDate
    // ),
    // ),
    // array(
    // 'title' => 'Eindstand',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $medewerkers,
    // 'data' => $this->IzVrijwilliger->count_per_coordinator(
    // 'eindstand', $startDate, $endDate
    // ),
    // ),
    // );

    // $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    // }

    // private function report_vrijwilligers_project_stadsdeel(
    // \DateTime $startDate,
    // \DateTime $endDate,
    // $format = 'html'
    // ) {
    // $this->loadModel('IzVrijwilliger');
    // $title = 'Vrijwilligers per project en stadsdeel';
    // $projecten = $this->getIzProjecten();
    // $reports = array(
    // array(
    // 'title' => 'Beginstand',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $projecten,
    // 'data' => $this->IzVrijwilliger->count_per_project_stadsdeel(
    // 'beginstand', $startDate, $endDate
    // ),
    // ),
    // array(
    // 'title' => 'Gestart',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $projecten,
    // 'data' => $this->IzVrijwilliger->count_per_project_stadsdeel(
    // 'gestart', $startDate, $endDate
    // ),
    // ),
    // // array(
    // // 'title' => 'Nieuw Gestart',
    // // 'xDescription' => '',
    // // 'yDescription' => '',
    // // 'data' => $this->IzVrijwilliger->count_per_project_stadsdeel(
    // // 'nieuw_gestart', $startDate, $endDate
    // // ),
    // // ),
    // array(
    // 'title' => 'Afgesloten',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $projecten,
    // 'data' => $this->IzVrijwilliger->count_per_project_stadsdeel(
    // 'afgesloten', $startDate, $endDate
    // ),
    // ),
    // array(
    // 'title' => 'Eindstand',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $projecten,
    // 'data' => $this->IzVrijwilliger->count_per_project_stadsdeel(
    // 'eindstand', $startDate, $endDate
    // ),
    // ),
    // );

    // $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    // }

    // private function report_vrijwilligers_postcodegebied(
    // \DateTime $startDate,
    // \DateTime $endDate,
    // $format = 'html'
    // ) {
    // $this->loadModel('IzVrijwilliger');

    // $title = 'Vrijwilligers per postcodegebied';
    // $reports = array(
    // array(
    // 'title' => 'Beginstand',
    // 'xDescription' => 'Aantal vrijwilligers',
    // 'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // 'data' => $this->IzVrijwilliger->count_per_postcodegebied(
    // 'beginstand', $startDate, $endDate
    // ),
    // ),
    // array(
    // 'title' => 'Gestart',
    // 'xDescription' => 'Aantal vrijwilligers',
    // 'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // 'data' => $this->IzVrijwilliger->count_per_postcodegebied(
    // 'gestart', $startDate, $endDate
    // ),
    // ),
    // // array(
    // // 'title' => 'Nieuw gestart',
    // // 'xDescription' => 'Aantal vrijwilligers',
    // // 'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // // 'data' => $this->IzVrijwilliger->count_per_postcodegebied(
    // // 'nieuw_gestart', $startDate, $endDate
    // // ),
    // // ),
    // array(
    // 'title' => 'Afgesloten',
    // 'xDescription' => 'Aantal vrijwilligers',
    // 'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // 'data' => $this->IzVrijwilliger->count_per_postcodegebied(
    // 'afgesloten', $startDate, $endDate
    // ),
    // ),
    // // array(
    // // 'title' => 'Succesvol afgesloten',
    // // 'xDescription' => 'Aantal vrijwilligers',
    // // 'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // // 'data' => $this->IzVrijwilliger->count_per_postcodegebied(
    // // 'succesvol_afgesloten', $startDate, $endDate
    // // ),
    // // ),
    // array(
    // 'title' => 'Eindstand',
    // 'xDescription' => 'Aantal vrijwilligers',
    // 'yDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // 'data' => $this->IzVrijwilliger->count_per_postcodegebied(
    // 'eindstand', $startDate, $endDate
    // ),
    // ),
    // );

    // $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    // }

    // private function report_vrijwilligers_project_postcodegebied(
    // \DateTime $startDate,
    // \DateTime $endDate,
    // $format = 'html'
    // ) {
    // $this->loadModel('IzVrijwilliger');
    // $title = 'Vrijwilligers met koppeling per project en postcodegebied';
    // $projecten = $this->getIzProjecten();
    // $reports = array(
    // array(
    // 'title' => 'Beginstand',
    // 'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // 'yDescription' => 'Project',
    // 'yLookupCollection' => $projecten,
    // 'data' => $this->IzVrijwilliger->count_per_project_postcodegebied(
    // 'beginstand', $startDate, $endDate
    // ),
    // ),
    // array(
    // 'title' => 'Gestart',
    // 'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // 'yDescription' => 'Project',
    // 'yLookupCollection' => $projecten,
    // 'data' => $this->IzVrijwilliger->count_per_project_postcodegebied(
    // 'gestart', $startDate, $endDate
    // ),
    // ),
    // // array(
    // // 'title' => 'Nieuw gestart',
    // // 'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // // 'yDescription' => 'Project',
    // // 'data' => $this->IzVrijwilliger->count_per_project_postcodegebied_nieuw_gestart(
    // // '', $startDate, $endDate
    // // ),
    // // ),
    // array(
    // 'title' => 'Afgesloten',
    // 'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // 'yDescription' => 'Project',
    // 'yLookupCollection' => $projecten,
    // 'data' => $this->IzVrijwilliger->count_per_project_postcodegebied(
    // 'afgesloten', $startDate, $endDate
    // ),
    // ),
    // // array(
    // // 'title' => 'Succesvol afgesloten',
    // // 'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // // 'yDescription' => 'Project',
    // // 'data' => $this->IzVrijwilliger->count_per_project_postcodegebied(
    // // 'succesvol_afgesloten', $startDate, $endDate
    // // ),
    // // ),
    // array(
    // 'title' => 'Eindstand',
    // 'xDescription' => 'Postcodegebied op basis van woonadres vrijwilliger',
    // 'yDescription' => 'Project',
    // 'yLookupCollection' => $projecten,
    // 'data' => $this->IzVrijwilliger->count_per_project_postcodegebied(
    // 'eindstand', $startDate, $endDate
    // ),
    // ),
    // );

    // $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    // }

    // private function report_klanten_aanmeldingen(
    // \DateTime $startDate,
    // \DateTime $endDate,
    // $format = 'html'
    // ) {
    // $this->loadModel('IzKlant');
    // $title = 'Klanten | Aanmeldingen';
    // $reports = array(
    // array(
    // 'title' => 'Aanmeldingen',
    // 'xDescription' => 'Aanmeldingen en daaruit volgende intakes, hulpvragen en koppelingen binnen datumbereik.',
    // 'yDescription' => '',
    // 'data' => $this->IzKlant->count_aanmeldingen(
    // $startDate,
    // $endDate
    // ),
    // ),
    // );

    // $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    // }

    // private function report_klanten_aanmeldingen_coordinator(
    // \DateTime $startDate,
    // \DateTime $endDate,
    // $format = 'html'
    // ) {
    // $this->loadModel('IzKlant');
    // $title = 'Klanten | Aanmeldingen per coördinator';
    // $medewerkers = $this->getMedewerkers();
    // $reports = array(
    // array(
    // 'title' => 'Aanmeldingen per coördinator',
    // 'xDescription' => 'Aanmeldingen en daaruit volgende intakes, hulpvragen en koppelingen. Aanmeldingen binnen het datumbereik. Intakes, hulpvragen en koppelingen kunnen buiten datumbereik liggen.',
    // 'yDescription' => 'Medewerkers uit basisdossier vrijwilliger (aanmeldingen), van IZ-intake (intakes), coördinatoren hulpvraag (hulpvragen en koppelingen)',
    // 'yLookupCollection' => $medewerkers,
    // 'data' => $this->IzKlant->count_aanmeldingen_per_coordinator(
    // $startDate,
    // $endDate
    // ),
    // ),
    // );

    // $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    // }

    // private function report_klanten_coordinator(
    // \DateTime $startDate,
    // \DateTime $endDate,
    // $format = 'html'
    // ) {
    // $this->loadModel('IzKlant');
    // $title = 'Klanten per coördinator';
    // $medewerkers = $this->getMedewerkers();
    // $reports = array(
    // array(
    // 'title' => 'Beginstand',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $medewerkers,
    // 'data' => $this->IzKlant->count_per_coordinator(
    // 'beginstand', $startDate, $endDate
    // ),
    // ),
    // array(
    // 'title' => 'Gestart',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $medewerkers,
    // 'data' => $this->IzKlant->count_per_coordinator(
    // 'gestart', $startDate, $endDate
    // ),
    // ),
    // array(
    // 'title' => 'Afgesloten',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $medewerkers,
    // 'data' => $this->IzKlant->count_per_coordinator(
    // 'afgesloten', $startDate, $endDate
    // ),
    // ),
    // array(
    // 'title' => 'Eindstand',
    // 'xDescription' => '',
    // 'yDescription' => '',
    // 'yLookupCollection' => $medewerkers,
    // 'data' => $this->IzKlant->count_per_coordinator(
    // 'eindstand', $startDate, $endDate
    // ),
    // ),
    // );

    // $this->set(compact('title', 'startDate', 'endDate', 'reports'));
    // }
}
