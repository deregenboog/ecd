<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use InloopBundle\Entity\Deelname;
use InloopBundle\Entity\Memo;
use InloopBundle\Form\DeelnameType;
use InloopBundle\Form\MemoType;
use InloopBundle\Service\MemoDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use InloopBundle\Service\DeelnameDaoInterface;

/**
 * @Route("/deelnames")
 */
class DeelnamesController extends AbstractChildController
{
    protected $title = 'Trainingsdeelnames';
    protected $entityName = 'deelname';
    protected $entityClass = Deelname::class;
    protected $formClass = DeelnameType::class;
    protected $addMethod = 'addDeelname';
    protected $deleteMethod = 'removeDeelname';
    protected $baseRouteName = 'inloop_deelname_';

    /**
     * @var DeelnameDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("InloopBundle\Service\DeelnameDao");
        $this->entities = $container->get("inloop.deelname.entities");
    
        return $previous;
    }
}
