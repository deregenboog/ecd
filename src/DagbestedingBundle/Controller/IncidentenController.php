<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\AbstractController;
use AppBundle\Controller\AbstractIncidentenController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Entity\BaseIncident;
use AppBundle\Service\IncidentDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use AppBundle\Controller\IncidentenController as AppIncidenetenController;
use AppBundle\Service\IncidentDao;
use DagbestedingBundle\Entity\Incident;
use DagbestedingBundle\Form\IncidentType;

/**
 * @Route("/deelnemers/incidenten")
 *
 * @Template
 * 
 */
class IncidentenController extends AppIncidenetenController
{
    use DisableIndexActionTrait;

    protected $entityName = 'Incident';
    protected $entityClass = Incident::class;
    protected $formClass = IncidentType::class;
    protected $baseRouteName = 'app_klanten_';
    protected $addMethod = 'addIncident';
}
