<?php

namespace HsBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Entity\Document;
use HsBundle\Form\DocumentType;

trait DocumentenAddControllerTrait
{
    /**
     * @Route("/{id}/documenten/add")
     */
    public function documenten_add($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(DocumentType::class, new Document($this->getMedewerker()));
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entity->addDocument($form->getData());
                $this->dao->update($entity);
                $this->addFlash('success', 'Document is toegevoegd.');
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
}
