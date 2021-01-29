<?php

namespace ClipBundle\Controller;


use AppBundle\Controller\MemosControllerAbstract;
use AppBundle\Service\MemoDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
class MemosController extends MemosControllerAbstract
{

    protected $baseRouteName = 'clip_memos_';

    /**
     * @var MemoDaoInterface
     *
     * @DI\Inject("AppBundle\Service\MemoDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("clip.memo.entities")
     */
    protected $entities;
}
