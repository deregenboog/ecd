<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\Afsluitreden;
use MwBundle\Form\AfsluitredenType;
use MwBundle\Service\AfsluitredenDao;
use MwBundle\Service\AfsluitredenDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
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
    protected $baseRouteName = 'mw_afsluitredenenvrijwilligers_';
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
