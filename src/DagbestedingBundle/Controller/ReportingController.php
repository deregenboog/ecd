<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use DagbestedingBundle\Form\ReportingType;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
