<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use IzBundle\Entity\Lidmaatschap;
use IzBundle\Form\LidmaatschapType;
use IzBundle\Service\LidmaatschapDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lidmaatschappen")
 */
class LidmaatschappenController extends AbstractChildController
{
    protected $title = 'Lidmaatschappen';
    protected $entityName = 'lidmaatschap';
    protected $entityClass = Lidmaatschap::class;
    protected $formClass = LidmaatschapType::class;
    protected $addMethod = 'addLidmaatschap';
    protected $baseRouteName = 'iz_lidmaatschappen_';
    protected $disabledActions = ['index', 'view', 'edit'];

    /**
     * @var LidmaatschapDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("IzBundle\Service\LidmaatschapDao");
        $this->entities = $container->get("iz.lidmaatschap.entities");
    
        return $previous;
    }
}
