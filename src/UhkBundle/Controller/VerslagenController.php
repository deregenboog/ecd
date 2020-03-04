<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use UhkBundle\Entity\Verslag;
use UhkBundle\Form\VerslagType;
use UhkBundle\Service\VerslagDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/verslagen")
 */
class VerslagenController extends AbstractChildController
{
    protected $title = 'Verslagen';
    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addVerslag';
    protected $baseRouteName = 'uhk_verslagen_';
    protected $disabledActions = ['index', 'view'];

    /**
     * @var VerslagDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("UhkBundle\Service\VerslagDao");
        $this->entities = $container->get("uhk.verslag.entities");
    
        return $previous;
    }
}
