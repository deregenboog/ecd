<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\AfsluitredenKoppeling;
use IzBundle\Form\AfsluitredenKoppelingType;
use IzBundle\Service\AfsluitredenKoppelingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Entity\SuccesindicatorPersoonlijk;
use IzBundle\Form\SuccesindicatorType;

/**
 * @Route("/admin/succesindicatorenpersoonlijk")
 */
class SuccesindicatorenPersoonlijkController extends SuccesindicatorenController
{
    protected $title = 'Succesindicatoren persoonlijk';
    protected $entityClass = SuccesindicatorPersoonlijk::class;
    protected $baseRouteName = 'iz_succesindicatorenpersoonlijk_';

    /**
     * @var SuccesindicatorDaoInterface
     *
     * @DI\Inject("IzBundle\Service\SuccesindicatorPersoonlijkDao")
     */
    protected $dao;
}
