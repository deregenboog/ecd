<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\AfsluitredenKoppeling;
use IzBundle\Form\AfsluitredenKoppelingType;
use IzBundle\Service\AfsluitredenKoppelingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Entity\SuccesindicatorParticipatie;

/**
 * @Route("/admin/succesindicatorenparticipatie")
 */
class SuccesindicatorenParticipatieController extends SuccesindicatorenController
{
    protected $title = 'Succesindicatoren participatie';
    protected $entityClass = SuccesindicatorParticipatie::class;
    protected $baseRouteName = 'iz_succesindicatorenparticipatie_';

    /**
     * @var SuccesindicatorDaoInterface
     *
     * @DI\Inject("IzBundle\Service\SuccesindicatorParticipatieDao")
     */
    protected $dao;
}
