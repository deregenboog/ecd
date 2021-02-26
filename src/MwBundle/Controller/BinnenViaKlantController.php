<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\BinnenViaOptieKlant;
use MwBundle\Form\BinnenViaOptieKlantType;
use MwBundle\Form\BinnenViaType;
use MwBundle\Service\BinnenViaDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/binnenviaKlant")
 */
class BinnenViaKlantController extends AbstractController
{
    protected $title = 'Binnen via-opties (Klant)';
    protected $entityName = 'binnen via-optie (Klant)';
    protected $entityClass = BinnenViaOptieKlant::class;
    protected $formClass = BinnenViaOptieKlantType::class;
    protected $baseRouteName = 'mw_binnenviaklant_';

    /**
     * @var BinnenViaDaoInterface
     *
     * @DI\Inject("MwBundle\Service\BinnenViaKlantDao")
     */
    protected $dao;
}
