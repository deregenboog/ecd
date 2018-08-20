<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\AfsluitredenKoppeling;
use IzBundle\Form\AfsluitredenKoppelingType;
use IzBundle\Service\AfsluitredenKoppelingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Entity\SuccesindicatorPersoonlijk;
use IzBundle\Form\SuccesindicatorType;

/**
 * @Route("/admin/succesindicatorenpersoonlijk")
 */
abstract class SuccesindicatorenController extends AbstractController
{
    protected $entityName = 'succesindicator';
    protected $formClass = SuccesindicatorType::class;

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
