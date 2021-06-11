<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\AbstractController;
use OdpBundle\Entity\Afsluitreden;
use OdpBundle\Form\AfsluitredenType;
use OdpBundle\Service\AfsluitredenDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/afsluitredenen")
 */
class AfsluitredenenVrijwilligersController extends AbstractController
{
    protected $title = 'Afsluitredenen vrijwilligers';
    protected $entityName = 'afsluitreden';
    protected $entityClass = Afsluitreden::class;
    protected $formClass = AfsluitredenType::class;
    protected $baseRouteName = 'odp_afsluitredenenvrijwilligers_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var AfsluitredenDaoInterface
     *
     * @DI\Inject("OdpBundle\Service\AfsluitredenDao")
     */
    protected $dao;
}
