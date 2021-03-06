<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\AbstractController;
use OdpBundle\Entity\BinnenVia;
use OdpBundle\Form\BinnenViaType;
use OdpBundle\Service\BinnenViaDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/binnenvia")
 */
class BinnenViaController extends AbstractController
{
    protected $title = 'Binnen via-opties';
    protected $entityName = 'binnen via-optie';
    protected $entityClass = BinnenVia::class;
    protected $formClass = BinnenViaType::class;
    protected $baseRouteName = 'odp_binnenvia_';

    /**
     * @var BinnenViaDaoInterface
     *
     * @DI\Inject("OdpBundle\Service\BinnenViaDao")
     */
    protected $dao;
}
