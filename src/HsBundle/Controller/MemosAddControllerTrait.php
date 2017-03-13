<?php

namespace HsBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Entity\Memo;
use HsBundle\Form\MemoType;
use AppBundle\Entity\Medewerker;

trait MemosAddControllerTrait
{
    /**
     * @Route("/{id}/memos/add")
     */
    public function memos_add($id)
    {
        $entity = $this->dao->find($id);

        $medewerkerId = $this->Session->read('Auth.Medewerker.id');
        $medewerker = $this->getEntityManager()->find(Medewerker::class, $medewerkerId);

        $memo = new Memo($medewerker);
        if (count($entity->getMemos()) === 0) {
            $memo->setIntake(true);
        }

        $form = $this->createForm(MemoType::class, $memo);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entity->addMemo($memo);
                $this->dao->update($entity);
                $this->addFlash('success', 'Memo is opgeslagen.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($entity->getId());
        }

        return [
            'klant' => $entity,
            'form' => $form->createView(),
        ];
    }
}
