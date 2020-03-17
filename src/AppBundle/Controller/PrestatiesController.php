<?php

namespace AppBundle\Controller;

use AppBundle\Exception\ReportException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\PrestatieType;
use AppBundle\Report\AbstractReport;
use AppBundle\Entity\Prestatie;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/app/prestaties")
 */
class PrestatiesController extends AbstractController
{
    protected $formClass = PrestatieType::class;
    protected $entityClass = Prestatie::class;
    protected $dao = PrestatieDao::class;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("app.export.report")
     */
    protected $export;

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

        $form = $this->getForm($this->formClass, null, $formOptions);
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

}
