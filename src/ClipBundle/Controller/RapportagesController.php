<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\ExportInterface;
use ClipBundle\Form\RapportageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        parent::setContainer($container);

        $this->export = $this->get("clip.export.report");
    
        return $container;
    }
}
