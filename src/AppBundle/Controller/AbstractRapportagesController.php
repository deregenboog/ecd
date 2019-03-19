<?php

namespace AppBundle\Controller;

use AppBundle\Report\AbstractReport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\ReportException;
use Symfony\Component\Form\FormError;

abstract class AbstractRapportagesController extends SymfonyController
{
    protected $title = 'Rapportages';

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
            // get reporting service
            /** @var AbstractReport $report */
            $report = $this->container->get($request->query->get('rapportage')['rapport']);
            $formOptions = $report->getFormOptions();
        }

        $form = $this->createForm($this->formClass, null, $formOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get reporting service
            /* @var AbstractReport $report */
            $report = $this->container->get($form->get('rapport')->getData());
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
