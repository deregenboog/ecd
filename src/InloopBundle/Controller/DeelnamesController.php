<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use InloopBundle\Entity\Deelname;
use InloopBundle\Entity\Memo;
use InloopBundle\Form\DeelnameType;
use InloopBundle\Form\MemoType;
use InloopBundle\Service\MemoDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

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
    protected $baseRouteName = 'inloop_deelname_';

    /**
     * @var DeelnameDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\DeelnameDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("inloop.deelname.entities")
     */
    protected $entities;
}
