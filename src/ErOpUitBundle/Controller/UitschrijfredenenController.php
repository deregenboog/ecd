<?php

namespace ErOpUitBundle\Controller;

use AppBundle\Controller\AbstractController;
use ErOpUitBundle\Entity\Uitschrijfreden;
use ErOpUitBundle\Form\UitschrijfredenType;
use ErOpUitBundle\Service\UitschrijfredenDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/uitschrijfredenen")
 */
class UitschrijfredenenController extends AbstractController
{
    protected $title = 'Uitschrijfredenen';
    protected $entityName = 'uitschrijfreden';
    protected $entityClass = Uitschrijfreden::class;
    protected $formClass = UitschrijfredenType::class;
    protected $baseRouteName = 'eropuit_uitschrijfredenen_';

    /**
     * @var UitschrijfredenDaoInterface
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("ErOpUitBundle\Service\UitschrijfredenDao");
    }
}
