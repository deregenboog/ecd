<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\ExportInterface;
use AppBundle\Export\ReportExport;
use GaBundle\Form\RapportageType;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;

    /**
     * @var ReportExport
     */
    protected $export;

    public function __construct(ExportInterface $export, ContainerInterface $container, iterable $reports)
    {
        $this->export = $export;
        $this->container = $container;
        $this->reports = $reports;
    }
}
