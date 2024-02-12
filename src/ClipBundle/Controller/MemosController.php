<?php

namespace ClipBundle\Controller;


use AppBundle\Controller\MemosControllerAbstract;
use AppBundle\Service\MemoDaoInterface;
use AppBundle\Service\MemoDao;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
class MemosController extends MemosControllerAbstract
{

    protected $baseRouteName = 'clip_memos_';

    /**
     * @var MemoDao
     *
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
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
