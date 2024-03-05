<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\AbstractController;
use AppBundle\Controller\DisableIndexActionTrait;
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
    use DisableIndexActionTrait;

    protected $entityName = 'Incident';
    protected $entityClass = Incident::class;
    protected $formClass = IncidentType::class;
    protected $baseRouteName = 'inloop_incidenten_';
    protected $addMethod = 'addIncident';

    /**
     * @var IncidentDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(IncidentDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

    /**
     * @Route("/addPrefilled/locatie/{locatie}")
     * @ParamConverter("locatie", class="InloopBundle\Entity\Locatie")
     * @Template("inloop/incidenten/add.html.twig")
     */
    public function addPrefilledAction(Request $request, Locatie $locatie)
    {
        $this->entity = new $this->entityClass();
        $this->entity->setLocatie($locatie);
        $this->entity->setDatum(new \DateTime("now"));

        return parent::addAction($request);
    }

}
