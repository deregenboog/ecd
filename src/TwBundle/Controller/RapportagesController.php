<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use AppBundle\Export\ExportInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Form\RapportageType;

/**
 * @Route("/rapportages")
 *
 * @Template
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;

    /**
     * @var ExportInterface
     */
    protected $export;
}
