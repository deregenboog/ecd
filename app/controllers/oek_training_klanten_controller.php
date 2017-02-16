<?php

use OekBundle\Entity\OekGroep;
use AppBundle\Form\ConfirmationType;
use OekBundle\Entity\OekKlant;
use OekBundle\Form\Model\OekTrainingKlantModel;
use OekBundle\Entity\OekTraining;
use OekBundle\Form\OekTrainingKlantType;

class OekTrainingKlantenController extends AppController
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

        $formModel = new OekTrainingKlantModel($oekTraining, $oekKlant);
        $form = $this->createForm(OekTrainingKlantType::class, $formModel);
        $form->handleRequest($this->request);
        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Deelnemer is aan training toegevoegd.');

            return $this->redirect(['controller' => 'oek_klanten', 'action' => 'view', $formModel->getOekKlant()->getId()]);
        }

        $this->set('form', $form->createView());
    }
}
