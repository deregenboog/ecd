<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/viapersonen")
 */
class ViaPersonenController extends AbstractController
{
    protected $title = 'Via personen';
    protected $entityName = 'via persoon';
    protected $entityClass = IzViaPersonen::class;
    protected $formClass = ViaPersonenType::class;
    protected $baseRouteName = 'iz_viapersonen_';

    /**
     * @var ViaPersonenDaoInterface
     *
     * @DI\Inject("IzBundle\Service\ViaPersonen")
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
