<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractRapportagesController;
use MwBundle\Form\RapportageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;
}
