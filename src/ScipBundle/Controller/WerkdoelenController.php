<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use ScipBundle\Entity\Werkdoel;
use ScipBundle\Form\WerkdoelType;
use ScipBundle\Service\WerkdoelDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/werkdoelen")
 */
class WerkdoelenController extends AbstractChildController
{
    protected $title = 'Werkdoelen';
    protected $entityName = 'werkdoel';
    protected $entityClass = Werkdoel::class;
    protected $formClass = WerkdoelType::class;
    protected $addMethod = 'addWerkdoel';
    protected $baseRouteName = 'scip_werkdoelen_';
    protected $disabledActions = ['index', 'view'];

    /**
     * @var WerkdoelDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("ScipBundle\Service\WerkdoelDao");
        $this->entities = $container->get("scip.werkdoel.entities");
    
        return $previous;
    }
}
