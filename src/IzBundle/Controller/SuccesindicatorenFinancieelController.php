<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\AfsluitredenKoppeling;
use IzBundle\Form\AfsluitredenKoppelingType;
use IzBundle\Service\AfsluitredenKoppelingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Entity\SuccesindicatorFinancieel;

/**
 * @Route("/admin/succesindicatorenfinancieel")
 */
class SuccesindicatorenFinancieelController extends SuccesindicatorenController
{
    protected $title = 'Succesindicatoren financieel';
    protected $entityClass = SuccesindicatorFinancieel::class;
    protected $baseRouteName = 'iz_succesindicatorenfinancieel_';

    /**
     * @var SuccesindicatorDaoInterface
     *
     * @DI\Inject("IzBundle\Service\SuccesindicatorFinancieelDao")
     */
    protected $dao;
}
