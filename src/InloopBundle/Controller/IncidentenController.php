<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\AbstractController;
use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Model\MedewerkerSubjectInterface;
use InloopBundle\Entity\Incident;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Training;
use AppBundle\Entity\Klant;
use InloopBundle\Entity\VwTraining;
use InloopBundle\Form\IncidentType;
use InloopBundle\Form\LocatieType;
use InloopBundle\Form\TrainingType;
use InloopBundle\Service\IncidentDaoInterface;
use InloopBundle\Service\KlantDaoInterface;
use InloopBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @var IncidentDaoInterface
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


    /**
     * @Route("/addPrefilled/locatie/{locatie}")
     * @ParamConverter("locatie", class="InloopBundle:Locatie")
     * @Template("@Inloop/incidenten/add.html.twig")
     */
    public function addPrefilledAction(Request $request, Locatie $locatie)
    {
        $this->entity = new $this->entityClass();
        $this->entity->setLocatie($locatie);
        $this->entity->setDatum(new \DateTime("now"));

        return parent::addAction($request);
    }

}
