<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Contactpersoon;
use DagbestedingBundle\Form\ContactpersoonType;
use DagbestedingBundle\Service\ContactpersoonDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contactpersonen")
 */
class ContactpersonenController extends AbstractChildController
{
    protected $title = 'Contactpersonen';
    protected $entityName = 'Contactpersoon';
    protected $entityClass = Contactpersoon::class;
    protected $formClass = ContactpersoonType::class;
    protected $addMethod = 'addContactpersoon';
    protected $baseRouteName = 'dagbesteding_contactpersonen_';

    /**
     * @var ContactpersoonDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("DagbestedingBundle\Service\ContactpersoonDao");
        $this->entities = $container->get("dagbesteding.contactpersoon.entities");
    
        return $previous;
    }
}
