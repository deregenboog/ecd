<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Memo;
use HsBundle\Form\MemoType;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Service\MemoDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Controller\AbstractChildController;

/**
 * @Route("/memos")
 */
class MemosController extends AbstractChildController
{
    protected $title = 'Memo\'s';
    protected $entityName = 'info';
    protected $entityClass = Memo::class;
    protected $formClass = MemoType::class;
    protected $addMethod = 'addMemo';
    protected $deleteMethod = 'removeMemo';
    protected $baseRouteName = 'hs_memos_';

    /**
     * @var MemoDaoInterface
     *
     * @DI\Inject("hs.dao.memo")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("hs.memo.entities")
     */
    protected $entities;
}
