<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\BinnenVia;
use InloopBundle\Form\BinnenViaType;
use InloopBundle\Service\BinnenViaDao;
use InloopBundle\Service\BinnenViaDaoInterface;
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
    protected $baseRouteName = 'inloop_binnenvia_';

    /**
     * @var BinnenViaDao
     */
    protected $dao;

    /**
     * @param BinnenViaDao $dao
     */
    public function __construct(BinnenViaDao $dao)
    {
        $this->dao = $dao;
    }


}
