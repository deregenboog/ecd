<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekraineBundle\Entity\BinnenVia;
use OekraineBundle\Form\BinnenViaType;
use OekraineBundle\Service\BinnenViaDaoInterface;
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
    protected $baseRouteName = 'oekraine_binnenvia_';

    /**
     * @var BinnenViaDaoInterface
     */
    protected $dao;

    public function __construct(BinnenViaDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
