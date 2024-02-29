<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait AjaxControllerTrait
{


    protected $dao;

    /**
     * @return void make sure that $formClass, $formOptions, $entityClass, $dao, $entityManager are existing
     */
    abstract protected function setPropertiesForAjax(): void;

    abstract public function beforeDelete($entity);
    abstract public function afterDelete($entity);

    abstract public function getForm($formClass, $entity, $formOptions);

    abstract public function renderView($template, $options);

    /**
     * @Route("/ajax/form/{id}", name="ajax_form", defaults={"id"=null}, methods={"GET"})
     */
    public function ajaxFormAction(Request $request, ?int $id = null): Response
    {
        $entity = $id ? $this->dao->find($id) : new $this->entityClass();
        if (!$entity) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->getForm($this->formClass, $entity, $this->formOptions);
        $formHtml = $this->renderView('path_to_your_form_template.html.twig', [
            'form' => $form->createView(),
        ]);

        return new JsonResponse(['formHtml' => $formHtml]);
    }

    /**
     * @Route("/ajax/submit/{id}", name="ajax_submit", defaults={"id"=null}, methods={"POST"})
     */
    public function ajaxSubmitAction(Request $request, ?int $id = null): Response
    {
        $entity = $id ? $this->dao->find($id) : new $this->entityClass();
        if (!$entity && $id) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->getForm($this->formClass, $entity, array_merge($this->formOptions, ['method' => 'POST']));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true, 'message' => 'Entity saved successfully']);
        }

        // Form not valid, return the form errors
        $errors = []; // Implement logic to extract form errors
        return new JsonResponse(['success' => false, 'errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/ajax/delete/{id}", name="ajax_delete", methods={"POST"})
     */
    public function ajaxDeleteAction(Request $request, int $id): Response
    {
        $entity = $this->dao->find($id);
        if (!$entity) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->beforeDelete($entity);
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            $this->afterDelete($entity);

            return new JsonResponse(['success' => true, 'message' => 'Entity deleted successfully']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Could not delete entity'], Response::HTTP_BAD_REQUEST);
        }
    }



}