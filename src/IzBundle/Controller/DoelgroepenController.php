<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Doelgroep;
use IzBundle\Form\DoelgroepType;
use IzBundle\Service\DoelgroepDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @var DoelgroepDaoInterface
     */
    protected $dao;

    public function __construct(DoelgroepDaoInterface $dao)
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
