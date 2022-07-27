<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Periode;
use InloopBundle\Form\PeriodeType;
use InloopBundle\Service\PeriodeDao;
use InloopBundle\Service\PeriodeDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
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
     * @var PeriodeDao
     */
    protected $dao;

    /**
     * @param PeriodeDao $dao
     */
    public function __construct(PeriodeDao $dao)
    {
        $this->dao = $dao;
    }


}
