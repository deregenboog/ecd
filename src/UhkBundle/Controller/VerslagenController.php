<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use JMS\DiExtraBundle\Annotation as DI;
use UhkBundle\Entity\Verslag;
use UhkBundle\Form\VerslagType;
use UhkBundle\Service\VerslagDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/verslagen")
 */
class VerslagenController extends AbstractChildController
{
    protected $title = 'Verslagen';
    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addVerslag';
    protected $baseRouteName = 'uhk_verslagen_';
    protected $disabledActions = ['index', 'view'];

    /**
     * @var VerslagDaoInterface
     *
     * @DI\Inject("UhkBundle\Service\VerslagDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("Uhk.verslag.entities")
     */
    protected $entities;
}
