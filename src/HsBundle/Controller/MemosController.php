<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use HsBundle\Entity\Memo;
use HsBundle\Form\MemoType;
use HsBundle\Service\MemoDaoInterface;
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
     * @var MemoDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("HsBundle\Service\MemoDao");
        $this->entities = $container->get("hs.memo.entities");
    
        return $previous;
    }
}
