<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Viacategorie;
use ClipBundle\Form\ViacategorieType;
use ClipBundle\Service\ViacategorieDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/viacategorieen")
 */
class ViacategorieenController extends AbstractController
{
    protected $title = 'Hoe bekend-opties';
    protected $entityName = 'hoe bekend-optie';
    protected $entityClass = Viacategorie::class;
    protected $formClass = ViacategorieType::class;
    protected $baseRouteName = 'clip_viacategorieen_';

    /**
     * @var ViacategorieDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        parent::setContainer($container);

        $this->dao = $this->get("ClipBundle\Service\ViacategorieDao");
    
        return $container;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
