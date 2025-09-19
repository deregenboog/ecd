<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\AbstractController;
use AppBundle\Controller\AbstractIncidentenController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Entity\BaseIncident;
use AppBundle\Service\IncidentDaoInterface;
use ErOpUitBundle\Service\IncidentDao;
use AppBundle\Form\IncidentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use AppBundle\Controller\IncidentenController as AppIncidenetenController;
use HsBundle\Entity\DienstverlenersIncident;

/**
 * @Route("/dienstverleners/incidenten")
 *
 * @Template
 * 
 */
class DienstverlenersIncidentenController extends AppIncidenetenController
{
    protected $entityClass = DienstverlenersIncident::class;
    protected $formClass = IncidentType::class;
    protected $baseRouteName = 'hs_dienstverleners_';
}
