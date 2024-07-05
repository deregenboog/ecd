<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\BinnenViaOptieVW;
use MwBundle\Form\BinnenViaOptieVWType;
use MwBundle\Service\BinnenViaDaoInterface;
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
     * @var BinnenViaDaoInterface
     */
    protected $dao;

    public function __construct(BinnenViaDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
