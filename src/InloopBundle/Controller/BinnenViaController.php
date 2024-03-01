<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\BinnenVia;
use InloopBundle\Form\BinnenViaType;
use InloopBundle\Service\BinnenViaDaoInterface;
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
     * @var BinnenViaDaoInterface
     */
    protected $dao;

    public function __construct(BinnenViaDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
