<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekBundle\Entity\VerwijzingDoor;
use OekBundle\Form\VerwijzingType;
use OekBundle\Service\VerwijzingDoorDao;
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
     * @var VerwijzingDoorDao
     */
    protected $dao;

    /**
     * @param VerwijzingDoorDao $dao
     */
    public function __construct(VerwijzingDoorDao $dao)
    {
        $this->dao = $dao;
    }


}
