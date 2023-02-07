<?php

namespace MwBundle\Controller;

use AppBundle\Controller\MemosControllerAbstract;
use GaBundle\Service\MemoDao;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
class MemosController extends MemosControllerAbstract
{
    /**
     * @var MemoDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(MemoDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
