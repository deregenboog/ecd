<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\BinnengekomenVia;
use IzBundle\Form\BinnengekomenViaType;
use IzBundle\Service\BinnengekomenViaDao;
use IzBundle\Service\BinnengekomenViaDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/binnengekomenviaopties")
 */
class BinnengekomenViaOptiesController extends AbstractController
{
    protected $title = 'Binnengekomen via-opties';
    protected $entityName = 'binnengekomen via-optie';
    protected $entityClass = BinnengekomenVia::class;
    protected $formClass = BinnengekomenViaType::class;
    protected $baseRouteName = 'iz_binnengekomenviaopties_';

    /**
     * @var BinnengekomenViaDao
     */
    protected $dao;

    /**
     * @param BinnengekomenViaDao $dao
     */
    public function __construct(BinnengekomenViaDao $dao)
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
