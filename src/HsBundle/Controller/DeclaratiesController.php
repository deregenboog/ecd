<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Declaratie;
use HsBundle\Form\DeclaratieType;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use HsBundle\Service\DeclaratieDaoInterface;
use AppBundle\Controller\AbstractChildController;
use HsBundle\Entity\Factuur;

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
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if ($entity->getFactuur() instanceof Factuur && $entity->getFactuur()->isLocked()) {
            return $this->redirectToRoute('hs_klussen_index');
        }

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if ($entity->getFactuur() instanceof Factuur && $entity->getFactuur()->isLocked()) {
            return $this->redirectToRoute('hs_klussen_index');
        }

        return parent::deleteAction($request, $id);
    }
}
