<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\AbstractController;
use OdpBundle\Entity\Locatie;
use OdpBundle\Entity\Training;
use OdpBundle\Entity\VwTraining;
use OdpBundle\Form\LocatieType;
use OdpBundle\Form\TrainingType;
use OdpBundle\Service\LocatieDaoInterface;
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
    protected $baseRouteName = 'odp_trainingen_';

    /**
     * @var LocatieDaoInterface
     *
     * @DI\Inject("OdpBundle\Service\TrainingDao")
     */
    protected $dao;
}
