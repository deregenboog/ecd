<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Periode;
use InloopBundle\Form\PeriodeType;
use InloopBundle\Service\PeriodeDaoInterface;
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
     * @var PeriodeDaoInterface
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("InloopBundle\Service\PeriodeDao");
    }
}
