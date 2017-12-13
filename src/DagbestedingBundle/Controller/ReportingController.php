<?php

namespace DagbestedingBundle\Controller;

use DagbestedingBundle\Form\ReportingType;
use Symfony\Component\Routing\Annotation\Route;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\AbstractRapportagesController;

/**
 * @Route("/reporting")
 */
class ReportingController extends AbstractRapportagesController
{
    protected $formClass = ReportingType::class;

    /**
     * @var GenericExport
     *
     * @DI\Inject("dagbesteding.export.report")
     */
    protected $export;

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }
}
