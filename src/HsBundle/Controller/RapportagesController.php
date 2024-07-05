<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use HsBundle\Form\RapportageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 *
 * @Template
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;
}
