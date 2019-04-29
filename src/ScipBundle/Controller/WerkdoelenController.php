<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use JMS\DiExtraBundle\Annotation as DI;
use ScipBundle\Entity\Werkdoel;
use ScipBundle\Form\WerkdoelType;
use ScipBundle\Service\WerkdoelDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/werkdoelen")
 */
class WerkdoelenController extends AbstractChildController
{
    protected $title = 'Werkdoelen';
    protected $entityName = 'werkdoel';
    protected $entityClass = Werkdoel::class;
    protected $formClass = WerkdoelType::class;
    protected $addMethod = 'addWerkdoel';
    protected $baseRouteName = 'oek_werkdoelen_';
    protected $disabledActions = ['index', 'view', 'deleted'];

    /**
     * @var WerkdoelDaoInterface
     *
     * @DI\Inject("ScipBundle\Service\WerkdoelDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("scip.werkdoel.entities")
     */
    protected $entities;
}
