<?php

use GaBundle\Form\GaRapportageType;

class GroepsactiviteitenRapportagesController extends AppController
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
        $form = $this->createForm(GaRapportageType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
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
}
