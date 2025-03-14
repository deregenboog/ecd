<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekraineBundle\Entity\Afsluitreden;
use OekraineBundle\Form\AfsluitredenType;
use OekraineBundle\Service\AfsluitredenDaoInterface;
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
    protected $baseRouteName = 'oekraine_afsluitredenenvrijwilligers_';
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
