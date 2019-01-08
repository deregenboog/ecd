<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Factuur;
use HsBundle\Form\DeclaratieType;
use HsBundle\Service\DeclaratieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/declaraties")
 * @Template
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
    public function viewAction(Request $request, $id)
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
