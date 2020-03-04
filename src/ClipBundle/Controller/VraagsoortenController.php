<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Vraagsoort;
use ClipBundle\Form\VraagsoortType;
use ClipBundle\Service\VraagsoortDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/vraagsoorten")
 */
class VraagsoortenController extends AbstractController
{
    protected $title = 'Onderwerpen';
    protected $entityName = 'onderwerp';
    protected $entityClass = Vraagsoort::class;
    protected $formClass = VraagsoortType::class;
    protected $baseRouteName = 'clip_vraagsoorten_';

    /**
     * @var VraagsoortDaoInterface
     *
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("ClipBundle\Service\VraagsoortDao");
    
        return $previous;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
