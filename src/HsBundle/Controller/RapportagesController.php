<?php

namespace HsBundle\Controller;

use AppBundle\Controller\SymfonyController;
use HsBundle\Form\RapportageType;
use HsBundle\Report\AbstractReport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hs/rapportages")
 */
class RapportagesController extends SymfonyController
{
    /**
     * @Route("/")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(RapportageType::class);
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

            $this->set($this->extractDataFromReport($report));
        }

        return ['form' => $form->createView()];
    }

    public function download(AbstractReport $report)
    {
        $data = $this->extractDataFromReport($report);

        foreach ($data['reports'] as &$subReport) {
            if ($firstRow = reset($subReport['data'])) {
                $subReport['columns'] = array_keys($firstRow);
                array_unshift($subReport['columns'], $subReport['yDescription']);
            } else {
                // Geen rijen dus geen kolommen.
                $subReport['columns'] = ['Geen data.'];
            }
        }

        $response = $this->render('@Hs/rapportages/download.csv.twig', $data);

        $filename = sprintf(
            '%s-%s-%s.xls',
            $report->getTitle(),
            $report->getStartDate()->format('d-m-Y'),
            $report->getEndDate()->format('d-m-Y')
        );

        $response->headers->set('Content-type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    private function extractDataFromReport(AbstractReport $report)
    {
        return [
            'title' => $report->getTitle(),
            'startDate' => $report->getStartDate(),
            'endDate' => $report->getEndDate(),
            'reports' => $report->getReports(),
        ];
    }
}
