<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use TwBundle\Entity\Locatie;
use TwBundle\Form\LocatieType;
use TwBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/locaties")
 * @Template
 */
class LocatiesController extends AbstractController
{
    protected $title = 'Locaties';
    protected $entityName = 'locatie';
    protected $entityClass = Locatie::class;
    protected $formClass = LocatieType::class;
    protected $baseRouteName = 'tw_locaties_';

    /**
     * @var LocatieDaoInterface
     *
     * @DI\Inject("TwBundle\Service\LocatieDao")
     */
    protected $dao;
}
