<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use InloopBundle\Entity\Locatietijd;
use InloopBundle\Form\LocatietijdType;
use InloopBundle\Service\LocatietijdDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/locatietijden")
 * @Template
 */
class LocatietijdenController extends AbstractChildController
{
    protected $title = 'Openingstijden';
    protected $entityName = 'openingstijd';
    protected $entityClass = Locatietijd::class;
    protected $formClass = LocatietijdType::class;
    protected $baseRouteName = 'inloop_locatietijden_';
    protected $addMethod = 'addLocatietijd';

    /**
     * @var LocatietijdDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("InloopBundle\Service\LocatietijdDao");
        $this->entities = $container->get("inloop.locatietijd.entities");
    
        return $previous;
    }
}
