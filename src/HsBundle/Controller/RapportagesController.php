<?php

namespace HsBundle\Controller;

use HsBundle\Form\RapportageType;
use HsBundle\Report\AbstractReport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\SymfonyController;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Export\GenericExport;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends SymfonyController
{
    public $title = 'Rapportages';

    /**
     * @var GenericExport
     *
     * @DI\Inject("hs.export.report")
     */
    protected $export;

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
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

        $this->autoRender = false;
        $filename = sprintf(
            '%s-%s-%s.xls',
            $report->getTitle(),
            $report->getStartDate()->format('d-m-Y'),
            $report->getEndDate()->format('d-m-Y')
        );

        return $this->export->create($data)->getResponse($filename);
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
