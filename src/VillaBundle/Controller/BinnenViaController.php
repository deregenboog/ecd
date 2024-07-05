<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use VillaBundle\Entity\BinnenVia;
use VillaBundle\Form\BinnenViaType;
use VillaBundle\Service\BinnenViaDaoInterface;

/**
 * @Route("/admin/binnenvia")
 */
class BinnenViaController extends AbstractController
{
    protected $title = 'Binnen via-opties';
    protected $entityName = 'binnen via-optie';
    protected $entityClass = BinnenVia::class;
    protected $formClass = BinnenViaType::class;
    protected $baseRouteName = 'villa_binnenvia_';

    /**
     * @var BinnenViaDaoInterface
     */
    protected $dao;

    public function __construct(BinnenViaDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
