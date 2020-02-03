<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use OekBundle\Entity\Deelname;
use OekBundle\Entity\Deelnemer;
use OekBundle\Entity\Training;
use OekBundle\Entity\DeelnameStatus;
use OekBundle\Form\DeelnameType;
use OekBundle\Form\DeelnemerFilterType;
use OekBundle\Form\DeelnemerType;
use OekBundle\Service\DeelnemerDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/deelnames")
 * @Template
 */
class DeelnamesController extends SymfonyController
{


    /**
     * @Route("/add")
     * @Template
     */
    public function addAction()
    {
        $entityManager = $this->getEntityManager();

        $training = null;
        if ($this->getRequest()->query->has('training')) {
            /** @var Training $training */
            $training = $entityManager->find(Training::class, $this->getRequest()->query->get('training'));
        }

        $deelnemer = null;
        if ($this->getRequest()->query->has('deelnemer')) {
            /** @var Deelnemer $deelnemer */
            $deelnemer = $entityManager->find(Deelnemer::class, $this->getRequest()->query->get('deelnemer'));
        }

        $deelname = new Deelname($training, $deelnemer);
        $form = $this->getForm(DeelnameType::class, $deelname);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($deelname);
            $entityManager->flush();

            $this->addFlash('success', 'Deelnemer is aan training toegevoegd.');

            return $this->redirectToRoute('oek_deelnemers_view', ['id' => $deelname->getDeelnemer()->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction($id)
    {
        $entityManager = $this->getEntityManager();

        $deelname = $entityManager->find(Deelname::class, $id);

        $form = $this->getForm(DeelnameType::class, $deelname, ['mode' => DeelnameType::MODE_EDIT]);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Deelnamestatus is gewijzigd.');

            return $this->redirectToRoute('oek_deelnemers_view', ['id' => $deelname->getDeelnemer()->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction($id)
    {

        $entityManager = $this->getEntityManager();

        $deelname = $entityManager->find(Deelname::class, $id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $deelname->setStatus(DeelnameStatus::STATUS_VERWIJDERD);
                //$entityManager->remove($deelname);
                $entityManager->flush();

                $this->addFlash('success', 'Deelnemer is van training verwijderd.');
            }

            return $this->redirectToRoute('oek_deelnemers_view', ['id' => $deelname->getDeelnemer()->getId()]);
        }

        return ['form' => $form->createView(), 'deelname' => $deelname];
    }
}
