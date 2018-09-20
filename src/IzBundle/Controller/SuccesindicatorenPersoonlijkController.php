<?php

namespace IzBundle\Controller;

use IzBundle\Entity\SuccesindicatorPersoonlijk;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
