<?php

namespace IzBundle\Controller;

use IzBundle\Entity\SuccesindicatorParticipatie;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
