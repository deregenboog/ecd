<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\AfsluitredenVrijwilliger;
use MwBundle\Form\AfsluitredenVrijwilligerType;
use MwBundle\Service\AfsluitredenVrijwilligerDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/afsluitredenen_vrijwilligers")
 */
class AfsluitredenenVrijwilligersController extends AbstractController
{
    protected $title = 'Afsluitredenen vrijwilligers';
    protected $entityName = 'afsluitreden';
    protected $entityClass = AfsluitredenVrijwilliger::class;
    protected $formClass = AfsluitredenVrijwilligerType::class;
    protected $baseRouteName = 'mw_afsluitredenenvrijwilligers_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var AfsluitredenVrijwilligerDaoInterface
     */
    protected $dao;

    public function __construct(AfsluitredenVrijwilligerDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
