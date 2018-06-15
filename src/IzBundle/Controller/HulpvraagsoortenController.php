<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Hulpvraagsoort;
use IzBundle\Form\HulpvraagsoortType;
use IzBundle\Service\HulpvraagsoortDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/hulpvraagsoorten")
 */
class HulpvraagsoortenController extends AbstractController
{
    protected $title = 'Hulpvraagsoorten';
    protected $entityName = 'hulpvraagsoort';
    protected $entityClass = Hulpvraagsoort::class;
    protected $formClass = HulpvraagsoortType::class;
    protected $baseRouteName = 'iz_hulpvraagsoorten_';

    /**
     * @var HulpvraagsoortDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpvraagsoortDao")
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
