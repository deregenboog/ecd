<?php

namespace InloopBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\BinnenVia;
use InloopBundle\Form\BinnenViaType;

/**
 * @Route("/admin/binnenvia")
 */
class BinnenViaController extends AbstractController
{
    protected $title = 'Binnen via-opties';
    protected $entityName = 'binnen via-optie';
    protected $entityClass = BinnenVia::class;
    protected $formClass = BinnenViaType::class;
    protected $baseRouteName = 'inloop_binnenvia_';

    /**
     * @var BinnenViaDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\BinnenViaDao")
     */
    protected $dao;
}
