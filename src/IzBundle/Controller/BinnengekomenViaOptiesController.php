<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\BinnengekomenVia;
use IzBundle\Form\BinnengekomenViaType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @var BinnengekomenViaDaoInterface
     *
     * @DI\Inject("IzBundle\Service\BinnengekomenViaDao")
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
