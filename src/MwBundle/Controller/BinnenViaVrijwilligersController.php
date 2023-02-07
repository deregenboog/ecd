<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\BinnenViaOptieVrijwilliger;
use MwBundle\Form\BinnenViaOptieVrijwilligerType;
use MwBundle\Service\BinnenViaVrijwilligerDao;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/binnen_via_vrijwilligers")
 */
class BinnenViaVrijwilligersController extends AbstractController
{
    protected $title = 'Binnen via-opties (vrijwilligers)';
    protected $entityName = 'binnen via-optie (vrijwilligers)';
    protected $entityClass = BinnenViaOptieVrijwilliger::class;
    protected $formClass = BinnenViaOptieVrijwilligerType::class;
    protected $baseRouteName = 'mw_binnenviavrijwilligers_';

    /**
     * @var BinnenViaVrijwilligerDao
     */
    protected $dao;

    public function __construct(BinnenViaVrijwilligerDao $dao)
    {
        $this->dao = $dao;
    }
}
