<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Doelgroep;
use IzBundle\Form\DoelgroepType;
use IzBundle\Service\DoelgroepDao;
use IzBundle\Service\DoelgroepDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/doelgroepen")
 */
class DoelgroepenController extends AbstractController
{
    protected $title = 'Doelgroepen';
    protected $entityName = 'doelgroep';
    protected $entityClass = Doelgroep::class;
    protected $formClass = DoelgroepType::class;
    protected $baseRouteName = 'iz_doelgroepen_';

    /**
     * @var DoelgroepDao
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
