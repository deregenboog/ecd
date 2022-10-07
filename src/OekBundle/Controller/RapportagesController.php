<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\GenericExport;
use AppBundle\Export\ReportExport;
use JMS\DiExtraBundle\Annotation as DI;
use OekBundle\Form\RapportageType;
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
     * @param ReportExport $export
     */
    public function __construct(ReportExport $export, $reports)
    {
        $this->export = $export;
        $this->reports = $reports;
    }


}
