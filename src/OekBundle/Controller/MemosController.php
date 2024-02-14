<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use OekBundle\Entity\Memo;
use OekBundle\Form\MemoType;
use OekBundle\Service\MemoDao;
use OekBundle\Service\MemoDaoInterface;
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
    protected $baseRouteName = 'oek_memos_';

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
