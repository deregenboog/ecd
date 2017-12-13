<?php

namespace ClipBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use JMS\DiExtraBundle\Annotation as DI;
use ClipBundle\Form\RapportageType;
use AppBundle\Export\ExportInterface;
use AppBundle\Controller\AbstractRapportagesController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.report")
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
