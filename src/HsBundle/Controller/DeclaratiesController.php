<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use HsBundle\Entity\Arbeider;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\FactuurSubjectHelper;
use HsBundle\Entity\Klus;
use HsBundle\Form\DeclaratieType;
use HsBundle\Service\DeclaratieDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/declaraties")
 *
 * @Template
 */
class DeclaratiesController extends AbstractChildController
{
    protected $entityName = 'declaratie';
    protected $entityClass = Declaratie::class;
    protected $formClass = DeclaratieType::class;
    protected $addMethod = 'addDeclaratie';
    protected $baseRouteName = 'hs_declaraties_';

    /**
     * @var DeclaratieDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(DeclaratieDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

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
        $declaratie = $this->dao->find($id);

        if ($declaratie->getFactuur() instanceof Factuur && $declaratie->getFactuur()->isLocked()) {
            return $this->redirectToRoute('hs_klussen_index');
        }

        return $this->processForm($request, $declaratie);
    }

    protected function beforeCreate($entity): void
    {
        $this->beforeUpdate($entity);
    }

    /**
     * @param Declaratie $declaratie
     *
     * @throws \HsBundle\Exception\InvoiceLockedException
     */
    protected function beforeUpdate($declaratie): void
    {
        $helper = new FactuurSubjectHelper();
        $helper->beforeUpdateEntity($declaratie, $this->getEntityManager());
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
        $entity = null;
        [$parentEntity] = $this->getParentConfig($request);
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
        }

        if ($parentEntity instanceof Klus) {
            $entity = new Declaratie($parentEntity);
        } elseif ($parentEntity instanceof Arbeider) {
            $entity = new Declaratie(null, $parentEntity);
        }

        $form = $this->getForm($this->formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->beforeCreate($entity);
                $this->dao->create($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is toegevoegd.');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
