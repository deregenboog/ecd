<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractController;
use VillaBundle\Entity\Afsluitreden;
use VillaBundle\Form\AfsluitredenType;
use VillaBundle\Service\AfsluitredenDao;
use VillaBundle\Service\AfsluitredenDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/afsluitredenen")
 */
class AfsluitredenenVrijwilligersController extends AbstractController
{
    protected $title = 'Afsluitredenen vrijwilligers';
    protected $entityName = 'afsluitreden';
    protected $entityClass = Afsluitreden::class;
    protected $formClass = AfsluitredenType::class;
    protected $baseRouteName = 'villa_afsluitredenenvrijwilligers_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var AfsluitredenDao
     */
    protected $dao;

    /**
     * @param AfsluitredenDao $dao
     */
    public function __construct(AfsluitredenDao $dao)
    {
        $this->dao = $dao;
    }
}
