<?php

namespace PfoBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\ExportInterface;
use AppBundle\Export\GenericExport;
use JMS\DiExtraBundle\Annotation as DI;
use PfoBundle\Form\RapportageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;

}
