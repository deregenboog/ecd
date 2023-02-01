<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\BinnenViaOptieVW;
use MwBundle\Form\BinnenViaOptieVWType;
use MwBundle\Form\BinnenViaType;
use MwBundle\Service\BinnenViaDao;
use MwBundle\Service\BinnenViaDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/binnenviavw")
 */
class BinnenViaVWController extends AbstractController
{
    protected $title = 'Binnen via-opties (vrijwilliger)';
    protected $entityName = 'binnen via-optie (vrijwilliger)';
    protected $entityClass = BinnenViaOptieVW::class;
    protected $formClass = BinnenViaOptieVWType::class;
    protected $baseRouteName = 'mw_binnenviavw_';

    /**
     * @var BinnenViaDao
     */
    protected $dao;

    public function __construct(BinnenViaDao $dao)
    {
        $this->dao = $dao;
    }
}
