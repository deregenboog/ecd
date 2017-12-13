<?php

namespace OdpBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Form\RapportageType;
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
     * @DI\Inject("odp.export.report")
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
