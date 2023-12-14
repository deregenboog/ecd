<?php

namespace AppBundle\Controller;

use AppBundle\Exception\AppException;
use AppBundle\Exception\ReportException;
use AppBundle\Export\ExportInterface;
use AppBundle\Report\AbstractReport;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRapportagesController extends SymfonyController
{
    protected $title = 'Rapportages';

    /**
     * @var iterable $reports
     */
    protected $reports;

    protected $formClass;

    protected $export;

    /**
     * @param ExportInterface $export
     */
    public function __construct(ExportInterface $export, iterable $reports)
    {
        $this->export = $export;
        $this->reports = $reports;
    }

    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request)
    {
        if (!$this->formClass) {
            throw new \InvalidArgumentException(get_class($this).'::formClass must be set.');
        }

        $formOptions = [];
        if ($request->query->has('rapportage')) {
            $rapport = $request->query->all('rapportage')['rapport'];
            // get reporting service
            /** @var AbstractReport $report */
            $report = $this->getReport($rapport);
            if(!$report) throw new AppException("Report cannot be found");

            $formOptions = $report->getFormOptions();
        }

        $form = $this->getForm($this->formClass, null, $formOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var AbstractReport $report */
            $report = $this->getReport($form->get('rapport')->getData());
            if(!$report) throw new AppException("Report not found");

            $report->setFilter($form->getData());

            try {
                if ($form->get('download')->isClicked()) {
                    return $this->download($report);
                }

                return [
                    'title' => $report->getTitle(),
                    'startDate' => $report->getStartDate(),
                    'endDate' => $report->getEndDate(),
                    'reports' => $report->getReports(),
                    'form' => $form->createView(),
                ];
            } catch (ReportException $exception) {
                $form->addError(new FormError($exception->getMessage()));

                return [
                    'form' => $form->createView(),
                    'title' => '',
                ];
            }
        }

        return [
            'form' => $form->createView(),
            'title' => '',
        ];
    }

    protected function getReport($name)
    {
        foreach($this->reports as $k=>$v)
        {
            $c = get_class($v);
            if($c == $name) return $v;
        }
        return false;
    }

    protected function download(AbstractReport $report)
    {
        ini_set('memory_limit', '512M');

        $data = [
            'title' => $report->getTitle(),
            'startDate' => $report->getStartDate(),
            'endDate' => $report->getEndDate(),
            'reports' => $report->getReports(),
        ];

        $filename = sprintf(
            '%s-%s-%s.xlsx',
            $report->getTitle(),
            $report->getStartDate()->format('d-m-Y'),
            $report->getEndDate()->format('d-m-Y')
        );

        return $this->export->create($data)->getResponse($filename);
    }


}
