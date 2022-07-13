<?php

namespace OekraineBundle\Controller;



use AppBundle\Controller\MemosControllerAbstract;
use AppBundle\Service\MemoDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
class MemosController extends MemosControllerAbstract
{

    /**
     * @var MemoDaoInterface
     *
     * @DI\Inject("AppBundle\Service\MemoDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("oekraine.memo.entities")
     */
    protected $entities;
}
