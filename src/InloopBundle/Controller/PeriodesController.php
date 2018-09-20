<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/periodes")
 * @Template
 */
class PeriodesController extends AbstractController
{
    protected $title = 'Periodes';
    protected $entityName = 'periode';
    protected $entityClass = Periode::class;
    protected $formClass = PeriodeType::class;
    protected $baseRouteName = 'inloop_periodes_';

    /**
     * @var PeriodeDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\PeriodeDao")
     */
    protected $dao;
}
