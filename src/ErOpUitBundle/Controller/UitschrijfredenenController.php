<?php

namespace ErOpUitBundle\Controller;

use AppBundle\Controller\AbstractController;
use ErOpUitBundle\Entity\Uitschrijfreden;
use ErOpUitBundle\Form\UitschrijfredenType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ErOpUitBundle\Service\UitschrijfredenDaoInterface;

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
     *
     * @DI\Inject("ErOpUitBundle\Service\UitschrijfredenDao")
     */
    protected $dao;
}
