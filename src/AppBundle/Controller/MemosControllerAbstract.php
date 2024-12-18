<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Memo;
use AppBundle\Form\MemoType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
abstract class MemosControllerAbstract extends AbstractChildController
{
    protected $title = 'Memo\'s';
    protected $entityName = 'memo';
    protected $entityClass = Memo::class;
    protected $formClass = MemoType::class;
    protected $addMethod = 'addMemo';
    protected $deleteMethod = 'removeMemo';
    protected $baseRouteName = 'inloop_memos_';
}
