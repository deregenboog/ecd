<?php

namespace AppBundle\Controller;

use AppBundle\Export\ExportInterface;
use AppBundle\Form\RapportageType;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/app/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;


    /**
     * @param ExportInterface $export
     */
    protected $export;

    /**
     * @var ContainerInterface
     */
    protected $container;



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
