<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use ClipBundle\Form\RapportageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;
}
