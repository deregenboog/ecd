<?php

namespace ErOpUitBundle\Controller;

use AppBundle\Controller\AbstractController;
use ErOpUitBundle\Entity\Uitschrijfreden;
use ErOpUitBundle\Form\UitschrijfredenType;
use ErOpUitBundle\Service\UitschrijfredenDao;
use ErOpUitBundle\Service\UitschrijfredenDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/uitschrijfredenen")
 */
class UitschrijfredenenController extends AbstractController
{
    protected $entityName = 'uitschrijfreden';
    protected $entityClass = Uitschrijfreden::class;
    protected $formClass = UitschrijfredenType::class;
    protected $baseRouteName = 'eropuit_uitschrijfredenen_';

    /**
     * @var UitschrijfredenDao
     */
    protected $dao;

    /**
     * @param UitschrijfredenDao $dao
     */
    public function __construct(UitschrijfredenDao $dao)
    {
        $this->dao = $dao;
    }


}
