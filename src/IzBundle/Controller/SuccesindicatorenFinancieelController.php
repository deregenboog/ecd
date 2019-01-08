<?php

namespace IzBundle\Controller;

use IzBundle\Entity\SuccesindicatorFinancieel;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
