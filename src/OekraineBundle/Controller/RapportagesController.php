<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use OekraineBundle\Form\RapportageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;
}
