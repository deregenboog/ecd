<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\ExportInterface;
use OekraineBundle\Form\RapportageType;
use JMS\DiExtraBundle\Annotation as DI;
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

    /**
     * @param ExportInterface $export
     */
    public function __construct(ExportInterface $export, iterable $reports)
    {
        $this->export = $export;
        $this->reports = $reports;
    }


}
