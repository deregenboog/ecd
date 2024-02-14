<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Hulpvraagsoort;
use IzBundle\Form\HulpvraagsoortType;
use IzBundle\Service\HulpvraagsoortDao;
use IzBundle\Service\HulpvraagsoortDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @var HulpvraagsoortDao
     */
    protected $dao;

    /**
     * @param HulpvraagsoortDao $dao
     */
    public function __construct(HulpvraagsoortDao $dao)
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
