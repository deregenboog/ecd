<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dossiers")
 */
class DossiersController extends AbstractController
{
    protected $title = 'Deelnemers';
    protected $entityName = 'deelnemer';
}
