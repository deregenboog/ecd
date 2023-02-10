<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\BinnenViaOptieKlant;
use MwBundle\Form\BinnenViaOptieKlantType;
use MwBundle\Form\BinnenViaType;
use MwBundle\Service\BinnenViaDao;
use MwBundle\Service\BinnenViaDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use MwBundle\Service\BinnenViaKlantDao;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/binnenviaKlant")
 */
class BinnenViaKlantController extends AbstractController
{
    protected $title = 'Binnen via-opties (klant)';
    protected $entityName = 'binnen via-optie (klant)';
    protected $entityClass = BinnenViaOptieKlant::class;
    protected $formClass = BinnenViaOptieKlantType::class;
    protected $baseRouteName = 'mw_binnenviaklant_';

    /**
     * @var BinnenViaKlantDao
     */
    protected $dao;

    public function __construct(BinnenViaKlantDao $dao)
    {
        $this->dao = $dao;
    }
}
