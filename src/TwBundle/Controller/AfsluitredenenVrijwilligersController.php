<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\Afsluitreden;
use TwBundle\Form\AfsluitredenType;
use TwBundle\Service\AfsluitredenDaoInterface;

/**
 * @Route("/afsluitredenen")
 */
class AfsluitredenenVrijwilligersController extends AbstractController
{
    protected $title = 'Afsluitredenen vrijwilligers';
    protected $entityName = 'afsluitreden';
    protected $entityClass = Afsluitreden::class;
    protected $formClass = AfsluitredenType::class;
    protected $baseRouteName = 'tw_afsluitredenenvrijwilligers_';
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
