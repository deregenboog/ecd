<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
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
     * @var GenericExport
     */
    protected $export;

    /**
     * @param GenericExport $export
     */
    public function __construct(GenericExport $export)
    {
        $this->export = $export;
    }


}
