<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Hulpvraagsoort;
use IzBundle\Form\HulpvraagsoortType;
use IzBundle\Service\HulpvraagsoortDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/hulpvraagsoorten")
 */
class HulpvraagsoortenController extends AbstractController
{
    protected $entityName = 'hulpvraagsoort';
    protected $entityClass = Hulpvraagsoort::class;
    protected $formClass = HulpvraagsoortType::class;
    protected $baseRouteName = 'iz_hulpvraagsoorten_';

    /**
     * @var HulpvraagsoortDaoInterface
     */
    protected $dao;

    public function __construct(HulpvraagsoortDaoInterface $dao)
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
