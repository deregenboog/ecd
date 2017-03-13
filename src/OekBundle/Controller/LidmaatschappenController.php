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
        if (isset($this->params['named']['oekGroep'])) {
            /** @var OekGroep $oekGroep */
            $oekGroep = $entityManager->find(OekGroep::class, $this->params['named']['oekGroep']);
        }

        $oekKlant = null;
        if (isset($this->params['named']['oekKlant'])) {
            /** @var OekKlant $oekKlant */
            $oekKlant = $entityManager->find(OekKlant::class, $this->params['named']['oekKlant']);
        }

        $oekLidmaatschap = new OekLidmaatschap($oekGroep, $oekKlant);
        $form = $this->createForm(OekLidmaatschapType::class, $oekLidmaatschap);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $entityManager->persist($oekLidmaatschap);
            $entityManager->flush();

            $this->Session->setFlash('Deelnemer is aan wachtlijst toegevoegd.');

            return $this->redirect(['controller' => 'oek_klanten', 'action' => 'view', $oekLidmaatschap->getOekKlant()->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($oekLidmaatschapId)
    {
        $entityManager = $this->getEntityManager();

        /** @var OekLidmaatschap $oekLidmaatschap */
        $oekLidmaatschap = $entityManager->find(OekLidmaatschap::class, $oekLidmaatschapId);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $entityManager->remove($oekLidmaatschap);
            $entityManager->flush();

            $this->Session->setFlash('Deelnemer is van wachtlijst verwijderd.');

            return $this->redirect(['controller' => 'oek_klanten', 'action' => 'view', $oekLidmaatschap->getOekKlant()->getId()]);
        }

        return ['form' => $form->createView(), 'oekLidmaatschap' => $oekLidmaatschap];
    }
}
