<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Form\RapportageType;
use AppBundle\Export\ExportInterface;

/**
 * @Route("/app/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("app.export.report")
     */
    protected $export;

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }
}
