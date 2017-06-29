<?php

namespace ClipBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use ClipBundle\Form\RapportageType;
use ClipBundle\Report\AbstractReport;
use AppBundle\Export\ExportInterface;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractController
{
    public $title = 'Rapportages';

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.report")
     */
    protected $export;

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $form = $this->createForm(RapportageType::class);
        $form->handleRequest($this->getRequest());

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

    public function download(AbstractReport $report)
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
