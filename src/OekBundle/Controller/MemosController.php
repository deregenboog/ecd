<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use OekBundle\Entity\Memo;
use OekBundle\Form\MemoType;
use OekBundle\Service\MemoDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
class MemosController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $title = 'Memo\'s';
    protected $entityName = 'info';
    protected $entityClass = Memo::class;
    protected $formClass = MemoType::class;
    protected $addMethod = 'addMemo';
    protected $deleteMethod = 'removeMemo';
    protected $baseRouteName = 'oek_memos_';

    /**
     * @var MemoDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(MemoDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
