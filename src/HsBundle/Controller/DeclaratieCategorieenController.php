<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractController;
use HsBundle\Entity\DeclaratieCategorie;
use HsBundle\Form\DeclaratieCategorieType;
use HsBundle\Service\DeclaratieCategorieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
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
     * @var DeclaratieCategorieDaoInterface
     *
     * @DI\Inject("HsBundle\Service\DeclaratieCategorieDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
