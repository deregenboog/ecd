<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractController;
use HsBundle\Entity\DeclaratieCategorie;
use HsBundle\Form\DeclaratieCategorieType;
use HsBundle\Service\DeclaratieCategorieDao;
use HsBundle\Service\DeclaratieCategorieDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/declaratiecategorieen")
 * @Template
 */
class DeclaratieCategorieenController extends AbstractController
{
    protected $title = 'Declaratiecategorieën';
    protected $entityName = 'declaratiecategorie';
    protected $entityClass = DeclaratieCategorie::class;
    protected $formClass = DeclaratieCategorieType::class;
    protected $baseRouteName = 'hs_declaratiecategorieen_';

    /**
     * @var DeclaratieCategorieDao
     */
    protected $dao;

    /**
     * @param DeclaratieCategorieDao $dao
     */
    public function __construct(DeclaratieCategorieDao $dao)
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
}
