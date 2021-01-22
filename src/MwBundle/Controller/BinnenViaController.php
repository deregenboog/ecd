<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\BinnenVia;
use MwBundle\Form\BinnenViaType;
use MwBundle\Service\BinnenViaDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/binnenvia")
 */
class BinnenViaController extends AbstractController
{
    protected $title = 'Binnen via-opties';
    protected $entityName = 'binnen via-optie';
    protected $entityClass = BinnenVia::class;
    protected $formClass = BinnenViaType::class;
    protected $baseRouteName = 'mw_binnenvia_';

    /**
     * @var BinnenViaDaoInterface
     *
     * @DI\Inject("MwBundle\Service\BinnenViaDao")
     */
    protected $dao;
}
