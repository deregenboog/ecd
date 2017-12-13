<?php

namespace OdpBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Form\RapportageType;
use OdpBundle\Report\AbstractReport;
use AppBundle\Export\ExportInterface;
use AppBundle\Controller\SymfonyController;
use AppBundle\Controller\RapportagesController as BaseController;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends BaseController
{
    public $title = 'Rapportages';

    /**
     * @var ExportInterface
     *
     * @DI\Inject("odp.export.report")
     */
    protected $export;
}
