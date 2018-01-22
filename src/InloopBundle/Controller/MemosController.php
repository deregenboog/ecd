<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use JMS\DiExtraBundle\Annotation as DI;
use InloopBundle\Entity\Memo;
use InloopBundle\Form\MemoType;
use InloopBundle\Service\MemoDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
class MemosController extends AbstractChildController
{
    protected $title = 'Memo\'s';
    protected $entityName = 'memo';
    protected $entityClass = Memo::class;
    protected $formClass = MemoType::class;
    protected $addMethod = 'addMemo';
    protected $deleteMethod = 'removeMemo';
    protected $baseRouteName = 'inloop_memos_';

    /**
     * @var MemoDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\MemoDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("inloop.memo.entities")
     */
    protected $entities;
}
