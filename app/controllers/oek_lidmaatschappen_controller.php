<?php

use OekBundle\Entity\OekGroep;
use AppBundle\Form\ConfirmationType;
use OekBundle\Form\OekLidmaatschapType;
use OekBundle\Entity\OekLidmaatschap;
use OekBundle\Entity\OekKlant;

class OekLidmaatschappenController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

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
        $form->handleRequest($this->request);
        if ($form->isValid()) {
            $entityManager->persist($oekLidmaatschap);
            $entityManager->flush();

            $this->Session->setFlash('Deelnemer is aan wachtlijst toegevoegd.');

            return $this->redirect(['controller' => 'oek_klanten', 'action' => 'view', $oekLidmaatschap->getOekKlant()->getId()]);
        }

        $this->set('form', $form->createView());
    }

    public function delete()
    {
        $entityManager = $this->getEntityManager();

        /** @var OekGroep $oekGroep */
        $oekGroep = $entityManager->find(OekGroep::class, $this->params['named']['oekGroep']);

        /** @var OekKlant $oekKlant */
        $oekKlant = $entityManager->find(OekKlant::class, $this->params['named']['oekKlant']);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $oekGroep->removeOekKlant($oekKlant);
            $entityManager->flush();

            $this->Session->setFlash('Deelnemer is van wachtlijst verwijderd.');

            return $this->redirect(['controller' => 'oek_klanten', 'action' => 'view', $oekKlant->getId()]);
        }

        $this->set('oekGroep', $oekGroep);
        $this->set('oekKlant', $oekKlant);
        $this->set('form', $form->createView());
    }
}
