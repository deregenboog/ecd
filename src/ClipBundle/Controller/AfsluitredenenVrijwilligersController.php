<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Afsluitreden;
use ClipBundle\Form\AfsluitredenType;
use ClipBundle\Service\AfsluitredenDao;
use ClipBundle\Service\AfsluitredenDaoInterface;
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
    protected $baseRouteName = 'clip_afsluitredenenvrijwilligers_';
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
