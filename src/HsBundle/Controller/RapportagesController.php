<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\GenericExport;
use HsBundle\Form\RapportageType;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
