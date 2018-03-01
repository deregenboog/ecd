<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Entity\IzEindeVraagAanbod;
use IzBundle\Form\EindeVraagAanbodType;
use IzBundle\Service\EindeVraagAanbodDaoInterface;

/**
 * @Route("/admin/eindevraagaanbod")
 */
class EindeVraagAanbodController extends AbstractController
{
    protected $title = 'Redenen einde hulpvraag/hulpaanbod';
    protected $entityName = 'reden einde hulpvraag/hulpaanbod';
    protected $entityClass = IzEindeVraagAanbod::class;
    protected $formClass = EindeVraagAanbodType::class;
    protected $baseRouteName = 'iz_eindevraagaanbod_';

    /**
     * @var EindeVraagAanbodDaoInterface
     *
     * @DI\Inject("IzBundle\Service\EindeVraagAanbodDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
