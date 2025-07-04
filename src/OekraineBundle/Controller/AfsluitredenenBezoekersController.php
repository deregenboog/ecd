<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekraineBundle\Entity\AfsluitredenBezoeker;
use OekraineBundle\Form\AfsluitredenBezoekerType;
use OekraineBundle\Service\AfsluitredenBezoekerDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/afsluitredenen_bezoekers")
 */
class AfsluitredenenBezoekersController extends AbstractController
{
    protected $title = 'Afsluitredenen bezoekers';
    protected $entityName = 'afsluitreden';
    protected $entityClass = AfsluitredenBezoeker::class;
    protected $formClass = AfsluitredenBezoekerType::class;
    protected $baseRouteName = 'oekraine_afsluitredenenbezoekers_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var AfsluitredenBezoekerDaoInterface
     */
    protected $dao;

    public function __construct(AfsluitredenBezoekerDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
