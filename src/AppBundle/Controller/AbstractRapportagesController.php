<?php

namespace AppBundle\Controller;

use AppBundle\Report\AbstractReport;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRapportagesController extends SymfonyController
{
    protected $title = 'Rapportages';

    public function indexAction(Request $request)
    {
        if (!$this->formClass) {
            throw new \InvalidArgumentException(get_class($this).'::formClass must be set.');
        }

        $form = $this->createForm($this->formClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get reporting service
            /** @var AbstractReport $report */
            $report = $this->container->get($form->get('rapport')->getData());

            // set options
            $report
                ->setStartDate($form->get('startdatum')->getData())
                ->setEndDate($form->get('einddatum')->getData())
            ;

            if ($form->get('download')->isClicked()) {
                return $this->download($report);
            }

            $data = $this->extractDataFromReport($report);
            $data['form'] = $form->createView();

            return $data;
        }

        return [
            'form' => $form->createView(),
            'title' => '',
        ];
    }

    protected function download(AbstractReport $report)
    {
        ini_set('memory_limit', '512M');

        $data = $this->extractDataFromReport($report);

        $filename = sprintf(
            '%s-%s-%s.xls',
            $report->getTitle(),
            $report->getStartDate()->format('d-m-Y'),
            $report->getEndDate()->format('d-m-Y')
        );

        return $this->export->create($data)->getResponse($filename);
    }

    protected function extractDataFromReport(AbstractReport $report)
    {
        return [
            'title' => $report->getTitle(),
            'startDate' => $report->getStartDate(),
            'endDate' => $report->getEndDate(),
            'reports' => $report->getReports(),
        ];
    }
}
