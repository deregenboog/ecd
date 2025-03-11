<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Controller\MemosControllerAbstract;
use OekraineBundle\Entity\Memo;
use OekraineBundle\Form\MemoType;
use OekraineBundle\Service\MemoDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
class MemosController extends MemosControllerAbstract
{
    use DisableIndexActionTrait;

    protected $baseRouteName = 'oekraine_memos_';
    protected $entityClass = Memo::class;
    protected $formClass = MemoType::class;
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
