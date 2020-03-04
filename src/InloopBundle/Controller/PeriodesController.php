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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("InloopBundle\Service\PeriodeDao");
    
        return $previous;
    }
}
