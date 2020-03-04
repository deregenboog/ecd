<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use OekBundle\Entity\Memo;
use OekBundle\Form\MemoType;
use OekBundle\Service\MemoDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/memos")
 */
class MemosController extends AbstractChildController
{
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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("OekBundle\Service\MemoDao");
        $this->entities = $container->get("oek.memo.entities");
    
        return $previous;
    }
}
