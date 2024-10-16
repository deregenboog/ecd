<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\AfsluitredenKoppeling;
use IzBundle\Form\AfsluitredenKoppelingType;
use IzBundle\Service\AfsluitredenKoppelingDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/afsluitredenenkoppeling")
 */
class AfsluitredenenKoppelingController extends AbstractController
{
    protected $title = 'Afsluitredenen koppelingen';
    protected $entityName = 'afsluitreden koppeling';
    protected $entityClass = AfsluitredenKoppeling::class;
    protected $formClass = AfsluitredenKoppelingType::class;
    protected $baseRouteName = 'iz_afsluitredenenkoppeling_';

    /**
     * @var AfsluitredenKoppelingDaoInterface
     */
    protected $dao;

    public function __construct(AfsluitredenKoppelingDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
