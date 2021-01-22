<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use MwBundle\Entity\Deelname;

use MwBundle\Form\DeelnameType;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use MwBundle\Service\DeelnameDaoInterface;

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
    protected $baseRouteName = 'mw_deelname_';

    /**
     * @var DeelnameDaoInterface
     *
     * @DI\Inject("MwBundle\Service\DeelnameDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("mw.deelname.entities")
     */
    protected $entities;
}
