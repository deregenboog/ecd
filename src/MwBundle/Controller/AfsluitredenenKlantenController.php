<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\AfsluitredenKlant;
use MwBundle\Form\AfsluitredenKlantType;
use MwBundle\Service\AfsluitredenKlantDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/afsluitredenen_klanten")
 */
class AfsluitredenenKlantenController extends AbstractController
{
    protected $title = 'Afsluitredenen klanten';
    protected $entityName = 'afsluitreden';
    protected $entityClass = AfsluitredenKlant::class;
    protected $formClass = AfsluitredenKlantType::class;
    protected $baseRouteName = 'mw_afsluitredenenklanten_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var AfsluitredenKlantDaoInterface
     */
    protected $dao;

    public function __construct(AfsluitredenKlantDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
