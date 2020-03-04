<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\AfsluitredenKoppeling;
use IzBundle\Form\AfsluitredenKoppelingType;
use IzBundle\Service\AfsluitredenKoppelingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("IzBundle\Service\AfsluitredenKoppelingDao");
    
        return $previous;
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
