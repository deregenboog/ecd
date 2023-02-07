<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\BinnenViaOptieKlant;
use MwBundle\Form\BinnenViaOptieKlantType;
use MwBundle\Service\BinnenViaKlantDao;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/binnen_via_klanten")
 */
class BinnenViaKlantenController extends AbstractController
{
    protected $title = 'Binnen via-opties (klanten)';
    protected $entityName = 'binnen via-optie (klanten)';
    protected $entityClass = BinnenViaOptieKlant::class;
    protected $formClass = BinnenViaOptieKlantType::class;
    protected $baseRouteName = 'mw_binnenviaklanten_';

    /**
     * @var BinnenViaKlantDao
     */
    protected $dao;

    public function __construct(BinnenViaKlantDao $dao)
    {
        $this->dao = $dao;
    }
}
