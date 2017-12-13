<?php

namespace HsBundle\Controller;

use HsBundle\Form\RapportageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Export\GenericExport;
use AppBundle\Controller\AbstractRapportagesController;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;

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
        return parent::indexAction($request);
    }
}
