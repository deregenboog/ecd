<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekBundle\Entity\VerwijzingDoor;
use OekBundle\Form\VerwijzingType;
use OekBundle\Service\VerwijzingDoorDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/verwijzingen_door")
 */
class VerwijzingenDoorController extends AbstractController
{
    protected $title = 'Verwijzingen door';
    protected $entityName = 'verwijzing door';
    protected $entityClass = VerwijzingDoor::class;
    protected $formClass = VerwijzingType::class;
    protected $baseRouteName = 'oek_verwijzingendoor_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var VerwijzingDoorDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("OekBundle\Service\VerwijzingDoorDao");
    
        return $previous;
    }
}
