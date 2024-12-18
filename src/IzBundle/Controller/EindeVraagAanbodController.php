<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\EindeVraagAanbod;
use IzBundle\Form\EindeVraagAanbodType;
use IzBundle\Service\EindeVraagAanbodDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/eindevraagaanbod")
 */
class EindeVraagAanbodController extends AbstractController
{
    protected $title = 'Redenen einde hulpvraag/hulpaanbod';
    protected $entityName = 'reden einde hulpvraag/hulpaanbod';
    protected $entityClass = EindeVraagAanbod::class;
    protected $formClass = EindeVraagAanbodType::class;
    protected $baseRouteName = 'iz_eindevraagaanbod_';

    /**
     * @var EindeVraagAanbodDaoInterface
     */
    protected $dao;

    public function __construct(EindeVraagAanbodDaoInterface $dao)
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
