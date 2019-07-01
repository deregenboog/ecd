<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Exception\AppException;
use HsBundle\Entity\Arbeider;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Registratie;
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
     * @DI\Inject("HsBundle\Service\DeclaratieDao")
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

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        list($parentEntity) = $this->getParentConfig($request);
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
        }

        if ($parentEntity instanceof Klus) {
            $entity = new Declaratie($parentEntity);
        } elseif ($parentEntity instanceof Arbeider) {
            $entity = new Declaratie(null, $parentEntity);
        }

        $form = $this->createForm($this->formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is toegevoegd.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToView($parentEntity);
        }

        return [
            'entity' => $entity,
            'parent_entity' => $parentEntity,
            'form' => $form->createView(),
        ];
    }
}
