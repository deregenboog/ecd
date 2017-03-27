<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use OekBundle\Entity\OekGroep;
use AppBundle\Form\ConfirmationType;
use OekBundle\Form\OekLidmaatschapType;
use OekBundle\Entity\OekLidmaatschap;
use OekBundle\Entity\OekKlant;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/oek/lidmaatschappen")
 */
class LidmaatschappenController extends SymfonyController
{
    /**
     * @Route("/add")
     */
    public function add()
    {
        $entityManager = $this->getEntityManager();

        $oekGroep = null;
        if ($this->getRequest()->query->has('oekGroep')) {
            /** @var OekGroep $oekGroep */
            $oekGroep = $entityManager->find(OekGroep::class, $this->getRequest()->query->get('oekGroep'));
        }

        $oekKlant = null;
        if ($this->getRequest()->query->has('oekKlant')) {
            /** @var OekKlant $oekKlant */
            $oekKlant = $entityManager->find(OekKlant::class, $this->getRequest()->query->get('oekKlant'));
        }

        $oekLidmaatschap = new OekLidmaatschap($oekGroep, $oekKlant);
        $form = $this->createForm(OekLidmaatschapType::class, $oekLidmaatschap);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $entityManager->persist($oekLidmaatschap);
            $entityManager->flush();

            $this->addFlash('success', 'Deelnemer is aan wachtlijst toegevoegd.');

            return $this->redirectToRoute('oek_klanten_view', ['id' => $oekLidmaatschap->getOekKlant()->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/delete/{oekGroep}/{oekKlant}")
     */
    public function delete($oekGroep, $oekKlant)
    {
        $entityManager = $this->getEntityManager();
        $repo = $entityManager->getRepository(OekLidmaatschap::class);

        /** @var OekLidmaatschap $oekLidmaatschap */
        $oekLidmaatschap = $repo->findOneBy(compact('oekGroep', 'oekKlant'));

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($oekLidmaatschap);
                $entityManager->flush();

                $this->addFlash('success', 'Deelnemer is van wachtlijst verwijderd.');
            }

            return $this->redirectToRoute('oek_klanten_view', ['id' => $oekLidmaatschap->getOekKlant()->getId()]);
        }

        return ['form' => $form->createView(), 'oekLidmaatschap' => $oekLidmaatschap];
    }
}
