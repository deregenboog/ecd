<?php

namespace HsBundle\Controller;

use AppBundle\Form\ConfirmationType;
use HsBundle\Entity\Memo;
use HsBundle\Form\MemoType;
use AppBundle\Controller\SymfonyController;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Service\MemoDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @Route("/hs/memos")
 */
class MemosController extends SymfonyController
{
    /**
     * @var MemoDaoInterface
     *
     * @DI\Inject("hs.dao.memo")
     */
    private $dao;

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(MemoType::class, $entity);
        $form->get('referer')->setData($this->referer());
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Memo is opgeslagen.');

            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirect($form->get('referer')->getData());
        }

        return [
            'memo' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->dao->delete($entity);
                    $this->addFlash('success', 'Memo is verwijderd.');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }
            }

            return $this->redirectToIndex();
        }

        return [
            'memo' => $entity,
            'form' => $form->createView(),
        ];
    }
}
