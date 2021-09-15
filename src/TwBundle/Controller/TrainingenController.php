<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use TwBundle\Entity\Locatie;
use TwBundle\Entity\Training;
use TwBundle\Entity\VwTraining;
use TwBundle\Form\LocatieType;
use TwBundle\Form\TrainingType;
use TwBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/trainingen")
 * @Template
 */
class TrainingenController extends AbstractController
{
    protected $title = 'Trainingen';
    protected $entityName = 'Training';
    protected $entityClass = Training::class;
    protected $formClass = TrainingType::class;
    protected $baseRouteName = 'tw_trainingen_';

    /**
     * @var LocatieDaoInterface
     *
     * @DI\Inject("TwBundle\Service\TrainingDao")
     */
    protected $dao;
}
