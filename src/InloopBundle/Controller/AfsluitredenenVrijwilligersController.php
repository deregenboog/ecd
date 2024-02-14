<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Afsluitreden;
use InloopBundle\Form\AfsluitredenType;
use InloopBundle\Service\AfsluitredenDao;
use InloopBundle\Service\AfsluitredenDaoInterface;
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
    protected $baseRouteName = 'inloop_afsluitredenenvrijwilligers_';
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
