<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\GenericExport;
use OekBundle\Form\RapportageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;

    /**
     * @var GenericExport
     */
    protected $export;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->export = $container->get("oek.export.report");
    
        return $previous;
    }
}
