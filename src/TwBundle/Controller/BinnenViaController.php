<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use TwBundle\Entity\BinnenVia;
use TwBundle\Form\BinnenViaType;
use TwBundle\Service\BinnenViaDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/binnenvia")
 */
class BinnenViaController extends AbstractController
{
    protected $title = 'Binnen via-opties';
    protected $entityName = 'binnen via-optie';
    protected $entityClass = BinnenVia::class;
    protected $formClass = BinnenViaType::class;
    protected $baseRouteName = 'tw_binnenvia_';

    /**
     * @var BinnenViaDaoInterface
     */
    protected $dao;

    public function __construct(BinnenViaDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
