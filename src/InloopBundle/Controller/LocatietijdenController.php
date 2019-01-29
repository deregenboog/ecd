<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Locatie;
use InloopBundle\Form\LocatieType;
use InloopBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InloopBundle\Entity\Locatietijd;
use InloopBundle\Form\LocatietijdType;
use InloopBundle\Service\LocatietijdDaoInterface;
use AppBundle\Controller\AbstractChildController;

/**
 * @Route("/admin/locatietijden")
 * @Template
 */
class LocatietijdenController extends AbstractChildController
{
    protected $title = 'Openingstijden';
    protected $entityName = 'openingstijd';
    protected $entityClass = Locatietijd::class;
    protected $formClass = LocatietijdType::class;
    protected $baseRouteName = 'inloop_locatietijden_';
    protected $addMethod = 'addLocatietijd';

    /**
     * @var LocatietijdDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\LocatietijdDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("inloop.locatietijd.entities")
     */
    protected $entities;
}
