<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use GaBundle\Entity\Memo;
use GaBundle\Form\MemoType;
use GaBundle\Service\MemoDao;
use Symfony\Component\Routing\Annotation\Route;

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
    protected $baseRouteName = 'ga_memos_';

    /**
     * @var MemoDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param MemoDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(MemoDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }


}
