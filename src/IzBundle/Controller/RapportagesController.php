<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use IzBundle\Form\RapportageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;
}
