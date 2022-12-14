<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use InloopBundle\Entity\Deelname;
use AppBundle\Entity\Memo;
use InloopBundle\Form\DeelnameType;
use AppBundle\Form\MemoType;
use AppBundle\Service\MemoDaoInterface;
use InloopBundle\Service\DeelnameDao;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use InloopBundle\Service\DeelnameDaoInterface;

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
     * @var DeelnameDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param DeelnameDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(DeelnameDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }


}
