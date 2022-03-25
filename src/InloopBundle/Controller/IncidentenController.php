<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Incident;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Training;
use InloopBundle\Entity\VwTraining;
use InloopBundle\Form\IncidentType;
use InloopBundle\Form\LocatieType;
use InloopBundle\Form\TrainingType;
use InloopBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/incidenten")
 * @Template
 */
class IncidentenController extends AbstractChildController
{
    protected $title = 'Incidenten';
    protected $entityName = 'Incident';
    protected $entityClass = Incident::class;
    protected $formClass = IncidentType::class;
    protected $baseRouteName = 'inloop_incidenten_';
    protected $addMethod = 'addIncident';

    /**
     * @var LocatieDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\IncidentDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("inloop.incident.entities")
     */
    protected $entities;
}
