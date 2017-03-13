<?php

namespace HsBundle\Controller;

use AppBundle\Form\ConfirmationType;
use HsBundle\Entity\Memo;
use HsBundle\Form\MemoType;
use AppBundle\Controller\SymfonyController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hs/memos")
 */
class MemosController extends SymfonyController
{
    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $memo = $entityManager->find(Memo::class, $id);

        $form = $this->createForm(MemoType::class, $memo);
        $form->get('referer')->setData($this->referer());
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Memo is opgeslagen.');

                return $this->redirect($form->get('referer')->getData());
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('memo', $memo);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $memo = $entityManager->find(Memo::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->get('referer')->setData($this->referer());
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($memo);
            $entityManager->flush();

            $this->addFlash('success', 'Memo is verwijderd.');

            return $this->redirect($form->get('referer')->getData());
        }

        $this->set('memo', $memo);
        $this->set('form', $form->createView());
    }
}
