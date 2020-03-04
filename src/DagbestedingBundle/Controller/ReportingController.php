<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\GenericExport;
use DagbestedingBundle\Form\ReportingType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reporting")
 */
class ReportingController extends AbstractRapportagesController
{
    protected $formClass = ReportingType::class;

    /**
     * @var GenericExport
     */
    protected $export;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->export = $container->get("dagbesteding.export.report");
    
        return $previous;
    }
}
