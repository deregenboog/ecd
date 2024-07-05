<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use VillaBundle\Entity\Afsluitreden;
use VillaBundle\Form\AfsluitredenType;
use VillaBundle\Service\AfsluitredenDaoInterface;

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
     * @var AfsluitredenDaoInterface
     */
    protected $dao;

    public function __construct(AfsluitredenDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
