<?php

namespace ErOpUitBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\AbstractController;
use AppBundle\Controller\AbstractIncidentenController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Entity\BaseIncident;
use AppBundle\Service\IncidentDaoInterface;
use ErOpUitBundle\Service\IncidentDao;
use ErOpUitBundle\Entity\Incident;
use ErOpUitBundle\Form\IncidentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use AppBundle\Controller\IncidentenController as AppIncidenetenController;

/**
 * @Route("/incidenten")
 *
 * @Template
 * 
 */
class IncidentenController extends AppIncidenetenController
{
    protected $entityClass = Incident::class;
    protected $formClass = IncidentType::class;
    protected $baseRouteName = 'eropuit_klanten_';
}
