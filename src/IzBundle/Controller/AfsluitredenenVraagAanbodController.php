<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Entity\EindeVraagAanbod;
use IzBundle\Service\EindeVraagAanbodDaoInterface;
use IzBundle\Form\EindeVraagAanbodType;
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
     * @var EindeVraagAanbodDaoInterface
     *
     * @DI\Inject("IzBundle\Service\EindeVraagAanbodDao")
     */
    protected $dao;

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
