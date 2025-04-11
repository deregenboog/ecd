<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\AbstractController;
use AppBundle\Controller\AbstractIncidentenController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Service\IncidentDaoInterface;
use MwBundle\Entity\Incident;
use InloopBundle\Entity\Locatie;
use MwBundle\Form\IncidentType;
use MwBundle\Service\IncidentDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/incidenten")
 *
 * @Template
 */
class IncidentenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'Incident';
    protected $entityClass = Incident::class;
    protected $formClass = IncidentType::class;
    protected $baseRouteName = 'mw_incidenten_';
    protected $addMethod = 'addIncident';

    /**
     * @var IncidentDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(IncidentDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

    /**
     * @Route("/addPrefilled/locatie/{locatie}")
     *
     * @ParamConverter("locatie", class="InloopBundle\Entity\Locatie")
     *
     * @Template("wm/incidenten/add.html.twig")
     */
    public function addPrefilledAction(Request $request, Locatie $locatie)
    {
        $this->entity = new $this->entityClass();
        $this->entity->setLocatie($locatie);
        $this->entity->setDatum(new \DateTime('now'));

        return parent::addAction($request);
    }
}
