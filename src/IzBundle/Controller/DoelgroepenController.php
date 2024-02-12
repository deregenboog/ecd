<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Doelgroep;
use IzBundle\Form\DoelgroepType;
use IzBundle\Service\DoelgroepDao;
use IzBundle\Service\DoelgroepDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/doelgroepen")
 */
class DoelgroepenController extends AbstractController
{
    protected $entityName = 'doelgroep';
    protected $entityClass = Doelgroep::class;
    protected $formClass = DoelgroepType::class;
    protected $baseRouteName = 'iz_doelgroepen_';

    /**
     * @var DoelgroepDao
     */
    protected $dao;

    /**
     * @param DoelgroepDao $dao
     */
    public function __construct(DoelgroepDao $dao)
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
