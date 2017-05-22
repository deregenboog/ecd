<?php

namespace AppBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use AppBundle\Service\AbstractDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AbstractController extends SymfonyController
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $formClass;

    /**
     * @var string
     */
    protected $filterFormClass;

    /**
     * @var string
     */
    protected $baseRouteName;

    /**
     * @var string
     */
    protected $templatePath;

    /**
     * @var AbstractDao
     */
    protected $dao;

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $filter = null;

        if ($this->filterFormClass) {
            $form = $this->createForm($this->filterFormClass);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $filter = $form->getData();
            }
        }

        $page = $request->get('page', 1);
        $pagination = $this->dao->findAll($page, $filter);

        return [
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return ['entity' => $this->dao->find($id)];
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm($this->formClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($form->getData());
                $this->addFlash('success', $this->entityName.' is opgeslagen.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($form->getData());
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm($this->formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', $this->entityName.' is opgeslagen.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $this->dao->delete($entity);
                $this->addFlash('success', $this->entityName.' is verwijderd.');

                return $this->redirectToIndex();
            } else {
                return $this->redirectToView($entity);
            }
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function getBaseRouteName()
    {
        return $this->baseRouteName;
    }

    protected function redirectToIndex()
    {
        if (!$this->baseRouteName) {
            throw new \RuntimeException(get_class($this).'::baseRouteName not set!');
        }

        return $this->redirectToRoute($this->baseRouteName.'index');
    }

    protected function redirectToView($entity)
    {
        if (!$this->baseRouteName) {
            throw new \RuntimeException(get_class($this).'::baseRouteName not set!');
        }

        return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $entity->getId()]);
    }
}
