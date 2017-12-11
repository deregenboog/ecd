<?php

namespace ClipBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Form\RapportageType;
use ClipBundle\Report\AbstractReport;
use AppBundle\Export\ExportInterface;
use AppBundle\Controller\SymfonyController;
use AppBundle\Controller\RapportagesController as BaseController

/**
 * @Route("/rapportages")
 */
class RapportagesController extends BaseController
{
    public $title = 'Rapportages';

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.report")
     */
    protected $export;
}
