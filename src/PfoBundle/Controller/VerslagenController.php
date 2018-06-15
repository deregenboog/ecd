<?php

namespace PfoBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use JMS\DiExtraBundle\Annotation as DI;
use PfoBundle\Entity\Verslag;
use PfoBundle\Form\VerslagType;
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
    protected $baseRouteName = 'oek_verslagen_';
    protected $disabledActions = ['deleted'];

    /**
     * @var VerslagDaoInterface
     *
     * @DI\Inject("PfoBundle\Service\VerslagDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("pfo.verslag.entities")
     */
    protected $entities;
}
