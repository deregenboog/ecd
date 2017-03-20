<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekTraining;
use OekBundle\Form\OekDeelnameType;
use OekBundle\Entity\OekDeelname;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/oek/deelnames")
 */
class DeelnamesController extends SymfonyController
{
    /**
     * @Route("/add")
     */
    public function add()
    {
        $entityManager = $this->getEntityManager();

        $oekTraining = null;
        if ($this->getRequest()->query->has('oekTraining')) {
            /** @var OekTraining $oekTraining */
            $oekTraining = $entityManager->find(OekTraining::class, $this->getRequest()->query->get('oekTraining'));
        }

        $oekKlant = null;
        if ($this->getRequest()->query->has('oekKlant')) {
            /** @var OekKlant $oekKlant */
            $oekKlant = $entityManager->find(OekKlant::class, $this->getRequest()->query->get('oekKlant'));
        }

        $oekDeelname = new OekDeelname($oekTraining, $oekKlant);
        $form = $this->createForm(OekDeelnameType::class, $oekDeelname);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $entityManager->persist($oekDeelname);
            $entityManager->flush();

            $this->addFlash('success', 'Deelnemer is aan training toegevoegd.');

            return $this->redirectToRoute('oek_klanten_view', ['id' => $oekDeelname->getOekKlant()->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/edit")
     */
    public function edit()
    {
        $entityManager = $this->getEntityManager();

        $oekTraining = null;
        if ($this->getRequest()->query->has('oekTraining')) {
            /** @var OekTraining $oekTraining */
            $oekTraining = $entityManager->find(OekTraining::class, $this->getRequest()->query->get('oekTraining'));
        }

        $oekKlant = null;
        if ($this->getRequest()->query->has('oekKlant')) {
            /** @var OekKlant $oekKlant */
            $oekKlant = $entityManager->find(OekKlant::class, $this->getRequest()->query->get('oekKlant'));
        }

        $oekDeelname = new OekDeelname($oekTraining, $oekKlant);
        $form = $this->createForm(OekDeelnameType::class, $oekDeelname, ['mode' => OekDeelnameType::MODE_EDIT]);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Deelnemer is aan training toegevoegd.');

            return $this->redirectToRoute('oek_klanten_view', ['id' => $oekDeelname->getOekKlant()->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/delete/{oekTraining}/{oekKlant}")
     */
    public function delete($oekTraining, $oekKlant)
    {
        $entityManager = $this->getEntityManager();
        $repo = $entityManager->getRepository(OekDeelname::class);

        /** @var OekDeelname $oekDeelname */
        $oekDeelname = $repo->findOneBy(compact('oekTraining', 'oekKlant'));

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($oekDeelname);
                $entityManager->flush();

                $this->addFlash('success', 'Deelnemer is van training verwijderd.');

            }

            return $this->redirectToRoute('oek_klanten_view', ['id' => $oekDeelname->getOekKlant()->getId()]);
        }

        return ['form' => $form->createView(), 'oekDeelname' => $oekDeelname];
    }
}
