<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\EindeVraagAanbod;
use IzBundle\Form\EindeVraagAanbodType;
use IzBundle\Service\EindeVraagAanbodDao;
use IzBundle\Service\EindeVraagAanbodDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/afsluitredenenvraagaanbod")
 */
class AfsluitredenenVraagAanbodController extends AbstractController
{
    protected $title = 'Afsluitredenen hulpvragen/-aanbiedingen';
    protected $entityName = 'afsluitreden hulpvraag/-aanbod';
    protected $entityClass = EindeVraagAanbod::class;
    protected $formClass = EindeVraagAanbodType::class;
    protected $baseRouteName = 'iz_afsluitredenenvraagaanbod_';

    /**
     * @var EindeVraagAanbodDao
     */
    protected $dao;

    /**
     * @param EindeVraagAanbodDao $dao
     */
    public function __construct(EindeVraagAanbodDao $dao)
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
