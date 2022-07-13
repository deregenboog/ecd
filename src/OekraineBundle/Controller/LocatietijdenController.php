<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use OekraineBundle\Entity\Locatietijd;
use OekraineBundle\Form\LocatietijdType;
use OekraineBundle\Service\LocatietijdDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
    protected $baseRouteName = 'oekraine_locatietijden_';
    protected $addMethod = 'addLocatietijd';

    /**
     * @var LocatietijdDaoInterface
     *
     * @DI\Inject("OekraineBundle\Service\LocatietijdDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("oekraine.locatietijd.entities")
     */
    protected $entities;
}
