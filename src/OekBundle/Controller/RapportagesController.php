<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use OekBundle\Form\OekRapportageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/oek/rapportages")
 */
class RapportagesController extends SymfonyController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $form = $this->createForm(OekRapportageType::class);
        $form->handleRequest($this->getRequest());

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

        return ['form' => $form->createView()];
    }
}
