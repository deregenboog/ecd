<?php

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekTraining;
use OekBundle\Form\OekDeelnameType;
use OekBundle\Entity\OekDeelname;

class OekDeelnamesController extends AppController
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

        $oekTraining = null;
        if (isset($this->params['named']['oekTraining'])) {
            /** @var OekTraining $oekTraining */
            $oekTraining = $entityManager->find(OekTraining::class, $this->params['named']['oekTraining']);
        }

        $oekKlant = null;
        if (isset($this->params['named']['oekKlant'])) {
            /** @var OekKlant $oekKlant */
            $oekKlant = $entityManager->find(OekKlant::class, $this->params['named']['oekKlant']);
        }

        $oekDeelname = new OekDeelname($oekTraining, $oekKlant);
        $form = $this->createForm(OekDeelnameType::class, $oekDeelname);
        $form->handleRequest($this->request);
        if ($form->isValid()) {
            $entityManager->persist($oekDeelname);
            $entityManager->flush();

            $this->Session->setFlash('Deelnemer is aan training toegevoegd.');

            return $this->redirect(['controller' => 'oek_klanten', 'action' => 'view', $oekDeelname->getOekKlant()->getId()]);
        }

        $this->set('form', $form->createView());
    }

    public function edit()
    {
        $entityManager = $this->getEntityManager();

        $oekTraining = null;
        if (isset($this->params['named']['oekTraining'])) {
            /** @var OekTraining $oekTraining */
            $oekTraining = $entityManager->find(OekTraining::class, $this->params['named']['oekTraining']);
        }

        $oekKlant = null;
        if (isset($this->params['named']['oekKlant'])) {
            /** @var OekKlant $oekKlant */
            $oekKlant = $entityManager->find(OekKlant::class, $this->params['named']['oekKlant']);
        }

        $oekDeelname = new OekDeelname($oekTraining, $oekKlant);
        $form = $this->createForm(OekDeelnameType::class, $oekDeelname, ['mode' => OekDeelnameType::MODE_EDIT]);
        $form->handleRequest($this->request);
        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Deelnemer is aan training toegevoegd.');

            return $this->redirect(['controller' => 'oek_klanten', 'action' => 'view', $oekDeelname->getOekKlant()->getId()]);
        }

        $this->set('form', $form->createView());
    }
}
