<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use TwBundle\Entity\Deelname;

use TwBundle\Form\DeelnameType;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Service\DeelnameDaoInterface;

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
    protected $baseRouteName = 'tw_deelname_';

    /**
     * @var DeelnameDaoInterface
     *
     * @DI\Inject("TwBundle\Service\DeelnameDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("tw.deelname.entities")
     */
    protected $entities;
}
