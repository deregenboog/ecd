<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Periode;
use InloopBundle\Form\PeriodeType;
use InloopBundle\Service\PeriodeDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/periodes")
 *
 * @Template
 */
class PeriodesController extends AbstractController
{
    protected $entityName = 'periode';
    protected $entityClass = Periode::class;
    protected $formClass = PeriodeType::class;
    protected $baseRouteName = 'inloop_periodes_';

    /**
     * @var PeriodeDaoInterface
     */
    protected $dao;

    public function __construct(PeriodeDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
