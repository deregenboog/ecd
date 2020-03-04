<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\BinnengekomenVia;
use IzBundle\Form\BinnengekomenViaType;
use IzBundle\Service\BinnengekomenViaDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/binnengekomenviaopties")
 */
class BinnengekomenViaOptiesController extends AbstractController
{
    protected $title = 'Binnengekomen via-opties';
    protected $entityName = 'binnengekomen via-optie';
    protected $entityClass = BinnengekomenVia::class;
    protected $formClass = BinnengekomenViaType::class;
    protected $baseRouteName = 'iz_binnengekomenviaopties_';

    /**
     * @var BinnengekomenViaDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("IzBundle\Service\BinnengekomenViaDao");
    
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
