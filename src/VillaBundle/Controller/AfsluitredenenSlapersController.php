<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\AfsluitredenKlant;
use MwBundle\Form\AfsluitredenKlantType;
use MwBundle\Service\AfsluitredenKlantDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use VillaBundle\Entity\AfsluitredenSlaper;
use VillaBundle\Form\AfsluitredenSlaperType;
use VillaBundle\Service\AfsluitredenSlaperDaoInterface;

/**
 * @Route("/admin/afsluitredenenslapers")
 */
class AfsluitredenenSlapersController extends AbstractController
{
    protected $title = 'Afsluitredenen slapers';
    protected $entityName = 'afsluitreden';
    protected $entityClass = AfsluitredenSlaper::class;
    protected $formClass = AfsluitredenSlaperType::class;
    protected $baseRouteName = 'villa_afsluitredenenslapers_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var AfsluitredenSlaperDaoInterface
     */
    protected $dao;

    public function __construct(AfsluitredenSlaperDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
