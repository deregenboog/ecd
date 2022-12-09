<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use HsBundle\Entity\Memo;
use HsBundle\Form\MemoType;
use HsBundle\Service\MemoDao;
use HsBundle\Service\MemoDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 * @Template
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
