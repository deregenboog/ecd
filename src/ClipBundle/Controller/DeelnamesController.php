<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use ClipBundle\Entity\Deelname;

use ClipBundle\Form\DeelnameType;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use ClipBundle\Service\DeelnameDaoInterface;

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
    protected $baseRouteName = 'clip_deelname_';

    /**
     * @var DeelnameDaoInterface
     *
     * @DI\Inject("ClipBundle\Service\DeelnameDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("clip.deelname.entities")
     */
    protected $entities;
}
