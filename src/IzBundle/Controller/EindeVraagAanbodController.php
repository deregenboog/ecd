<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\EindeVraagAanbod;
use IzBundle\Form\EindeVraagAanbodType;
use IzBundle\Service\EindeVraagAanbodDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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

    public function __construct()
    {
        $this->dao = $this->get("IzBundle\Service\EindeVraagAanbodDao");
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
