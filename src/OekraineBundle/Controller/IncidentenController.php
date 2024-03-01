<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\AbstractController;
use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Model\MedewerkerSubjectInterface;
use OekraineBundle\Service\IncidentDao;
use OekraineBundle\Entity\Incident;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Entity\Training;
use AppBundle\Entity\Klant;
use OekraineBundle\Entity\VwTraining;
use OekraineBundle\Form\IncidentType;
use OekraineBundle\Form\LocatieType;
use OekraineBundle\Form\TrainingType;
use OekraineBundle\Service\IncidentDaoInterface;
use OekraineBundle\Service\KlantDaoInterface;
use OekraineBundle\Service\LocatieDaoInterface;
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
    protected $entityName = 'Incident';
    protected $entityClass = Incident::class;
    protected $formClass = IncidentType::class;
    protected $baseRouteName = 'oekraine_incidenten_';
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
     * @ParamConverter("locatie", class="OekraineBundle\Entity\Locatie")
     * @Template("oekraine/incidenten/add.html.twig")
     */
    public function addPrefilledAction(Request $request, Locatie $locatie)
    {
        $this->entity = new $this->entityClass();
        $this->entity->setLocatie($locatie);
        $this->entity->setDatum(new \DateTime("now"));

        return parent::addAction($request);
    }

}
