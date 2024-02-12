<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\ExportInterface;
use AppBundle\Export\GenericExport;
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

    /**
     * @param ExportInterface $export
     * @param ContainerInterface $container
     * @param iterable $reports
     */
    public function __construct(ExportInterface $export, ContainerInterface $container, iterable $reports)
    {
        $this->export = $export;
        $this->container = $container;
        $this->reports = $reports;
    }


}
