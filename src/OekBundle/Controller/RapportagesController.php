<?php

namespace OekBundle\Controller;

use OekBundle\Form\OekRapportageType;
use AppBundle\Report\AbstractReport;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractRapportagesController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = OekRapportageType::class;

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
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

        $response = $this->render('@Oek/rapportages/download.csv.twig', $data);

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
}
