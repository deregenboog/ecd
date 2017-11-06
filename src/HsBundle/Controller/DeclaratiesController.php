<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Klus;
use HsBundle\Form\DeclaratieType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\AbstractController;
use HsBundle\Service\DeclaratieDaoInterface;
use AppBundle\Controller\AbstractChildController;

/**
 * @Route("/declaraties")
 */
class DeclaratiesController extends AbstractChildController
{
    protected $title = 'Declaraties';
    protected $entityName = 'declaratie';
    protected $entityClass = Declaratie::class;
    protected $formClass = DeclaratieType::class;
    protected $addMethod = 'addDeclaratie';
    protected $baseRouteName = 'hs_declaraties_';

    /**
     * @var DeclaratieDaoInterface
     *
     * @DI\Inject("hs.dao.declaratie")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("hs.declaratie.entities")
     */
    protected $entities;

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('hs_klussen_index');
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToRoute('hs_klussen_index');
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->redirectToRoute('hs_klussen_index');
    }
}
