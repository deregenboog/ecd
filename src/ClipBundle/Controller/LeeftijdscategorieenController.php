<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Leeftijdscategorie;
use ClipBundle\Form\LeeftijdscategorieType;
use ClipBundle\Service\LeeftijdscategorieDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/leeftijdscategorieen")
 */
class LeeftijdscategorieenController extends AbstractController
{
    protected $title = 'Leeftijdscategorieën';
    protected $entityName = 'leeftijdscategorie';
    protected $entityClass = Leeftijdscategorie::class;
    protected $formClass = LeeftijdscategorieType::class;
    protected $baseRouteName = 'clip_leeftijdscategorieen_';

    /**
     * @var LeeftijdscategorieDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("ClipBundle\Service\LeeftijdscategorieDao");
    
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
