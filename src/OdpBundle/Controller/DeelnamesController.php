<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use OdpBundle\Entity\Deelname;

use OdpBundle\Form\DeelnameType;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use OdpBundle\Service\DeelnameDaoInterface;

/**
 * @Route("/deelnames")
 */
class DeelnamesController extends AbstractChildController
{
    protected $title = 'Trainingsdeelnames';
    protected $entityName = 'deelname';
    protected $entityClass = Deelname::class;
    protected $formClass = DeelnameType::class;
    protected $addMethod = 'addDeelname';
    protected $deleteMethod = 'removeDeelname';
    protected $baseRouteName = 'odp_deelname_';

    /**
     * @var DeelnameDaoInterface
     *
     * @DI\Inject("OdpBundle\Service\DeelnameDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("odp.deelname.entities")
     */
    protected $entities;
}
