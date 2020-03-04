<?php

namespace AppBundle\Controller;

use AppBundle\Export\ExportInterface;
use AppBundle\Form\RapportageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app/rapportages")
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
        $previous = parent::setContainer($container);

        $this->export = $container->get("app.export.report");
    
        return $previous;
    }
}
