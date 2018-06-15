<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractController;
use HsBundle\Entity\DeclaratieCategorie;
use HsBundle\Form\DeclaratieCategorieType;
use HsBundle\Service\DeclaratieCategorieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/declaratiecategorieen")
 */
class DeclaratieCategorieenController extends AbstractController
{
    protected $title = 'Declaratiecategorieën';
    protected $entityName = 'declaratiecategorie';
    protected $entityClass = DeclaratieCategorie::class;
    protected $formClass = DeclaratieCategorieType::class;
    protected $baseRouteName = 'hs_declaratiecategorieen_';

    /**
     * @var DeclaratieCategorieDaoInterface
     *
     * @DI\Inject("hs.dao.declaratiecategorie")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }
}
