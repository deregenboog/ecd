<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\GenericExport;
use AppBundle\Export\ReportExport;
use GaBundle\Form\RapportageType;
use JMS\DiExtraBundle\Annotation as DI;
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
    public function __construct(ReportExport $export)
    {
        $this->export = $export;
    }


}
