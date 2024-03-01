<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\BinnenViaOptieKlant;
use MwBundle\Form\BinnenViaOptieKlantType;
use MwBundle\Form\BinnenViaType;
use MwBundle\Service\BinnenViaKlantDaoInterface;
use MwBundle\Service\BinnenViaDaoInterface;
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
     * @var BinnenViaDaoInterface
     */
    protected $dao;

    public function __construct(BinnenViaDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
