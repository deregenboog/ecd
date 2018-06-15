<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use OekBundle\Entity\Deelnemer;
use OekBundle\Entity\Groep;
use OekBundle\Entity\Lidmaatschap;
use OekBundle\Form\LidmaatschapType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lidmaatschappen")
 */
class LidmaatschappenController extends SymfonyController
{
    /**
     * @Route("/add")
     */
    public function addAction()
    {
        $entityManager = $this->getEntityManager();

        $groep = null;
        if ($this->getRequest()->query->has('groep')) {
            /** @var Groep $groep */
            $groep = $entityManager->find(Groep::class, $this->getRequest()->query->get('groep'));
        }

        $deelnemer = null;
        if ($this->getRequest()->query->has('deelnemer')) {
            /** @var Deelnemer $deelnemer */
            $deelnemer = $entityManager->find(Deelnemer::class, $this->getRequest()->query->get('deelnemer'));
        }

        $lidmaatschap = new Lidmaatschap($groep, $deelnemer);
        $form = $this->createForm(LidmaatschapType::class, $lidmaatschap);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lidmaatschap);
            $entityManager->flush();

            $this->addFlash('success', 'Deelnemer is aan wachtlijst toegevoegd.');

            return $this->redirectToRoute('oek_deelnemers_view', ['id' => $lidmaatschap->getDeelnemer()->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/delete/{groep}/{deelnemer}")
     */
    public function deleteAction(Groep $groep, Deelnemer $deelnemer)
    {
        $entityManager = $this->getEntityManager();
        $repo = $entityManager->getRepository(Lidmaatschap::class);

        /** @var Lidmaatschap $lidmaatschap */
        $lidmaatschap = $repo->findOneBy(['groep' => $groep, 'deelnemer' => $deelnemer]);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($lidmaatschap);
                $entityManager->flush();
                $this->addFlash('success', 'Deelnemer is van wachtlijst verwijderd.');
            }

            return $this->redirectToRoute('oek_deelnemers_view', ['id' => $lidmaatschap->getDeelnemer()->getId()]);
        }

        return ['form' => $form->createView(), 'lidmaatschap' => $lidmaatschap];
    }
}
