<?php

namespace VillaBundle\Controller;


use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Controller\MemosControllerAbstract;
use AppBundle\Service\MemoDao;
use AppBundle\Service\MemoDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
class MemosController extends MemosControllerAbstract
{
    use DisableIndexActionTrait;
    protected $baseRouteName = 'villa_memos_';

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