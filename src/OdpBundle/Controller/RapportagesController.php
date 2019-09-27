<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\ExportInterface;
use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Form\RapportageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 * @Template
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
}
