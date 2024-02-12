<?php

namespace TwBundle\Controller;


use AppBundle\Controller\MemosControllerAbstract;
use AppBundle\Service\MemoDaoInterface;
use GaBundle\Service\MemoDao;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
class MemosController extends MemosControllerAbstract
{

    protected $baseRouteName = 'tw_memos_';

    /**
     * @var \AppBundle\Service\MemoDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param \AppBundle\Service\MemoDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(\AppBundle\Service\MemoDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }


}
