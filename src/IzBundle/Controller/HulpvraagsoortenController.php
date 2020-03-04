<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Hulpvraagsoort;
use IzBundle\Form\HulpvraagsoortType;
use IzBundle\Service\HulpvraagsoortDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/hulpvraagsoorten")
 */
class HulpvraagsoortenController extends AbstractController
{
    protected $title = 'Hulpvraagsoorten';
    protected $entityName = 'hulpvraagsoort';
    protected $entityClass = Hulpvraagsoort::class;
    protected $formClass = HulpvraagsoortType::class;
    protected $baseRouteName = 'iz_hulpvraagsoorten_';

    /**
     * @var HulpvraagsoortDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("IzBundle\Service\HulpvraagsoortDao");
    
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
