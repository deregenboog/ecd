<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\ExportInterface;
use AppBundle\Export\GenericExport;
use DagbestedingBundle\Form\ReportingType;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reporting")
 */
class ReportingController extends AbstractRapportagesController
{
    protected $formClass = ReportingType::class;

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
