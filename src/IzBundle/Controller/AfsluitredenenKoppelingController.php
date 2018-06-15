<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\EindeKoppeling;
use IzBundle\Form\EindeKoppelingType;
use IzBundle\Service\EindeKoppelingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/afsluitredenenkoppeling")
 */
class AfsluitredenenKoppelingController extends AbstractController
{
    protected $title = 'Afsluitredenen koppelingen';
    protected $entityName = 'afsluitreden koppeling';
    protected $entityClass = EindeKoppeling::class;
    protected $formClass = EindeKoppelingType::class;
    protected $baseRouteName = 'iz_afsluitredenenkoppeling_';

    /**
     * @var EindeKoppelingDaoInterface
     *
     * @DI\Inject("IzBundle\Service\EindeKoppelingDao")
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
