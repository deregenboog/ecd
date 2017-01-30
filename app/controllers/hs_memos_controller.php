<?php

use AppBundle\Entity\Klant;
use HsBundle\Entity\HsKlant;
use HsBundle\Form\HsKlantType;
use AppBundle\Form\KlantFilterType;
use HsBundle\Form\HsKlantSelectType;
use Doctrine\DBAL\Driver\PDOException;
use HsBundle\Form\HsKlantFilterType;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use HsBundle\Entity\HsMemo;
use HsBundle\Form\HsMemoType;

class HsMemosController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $hsMemo = $entityManager->find(HsMemo::class, $id);

        $form = $this->createForm(HsMemoType::class, $hsMemo);
        $form->get('referer')->setData($this->referer());
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $entityManager->flush();

                $this->Session->setFlash('Memo is opgeslagen.');

                return $this->redirect($form->get('referer')->getData());
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('hsMemo', $hsMemo);
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $hsMemo = $entityManager->find(HsMemo::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->get('referer')->setData($this->referer());
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($hsMemo);
            $entityManager->flush();

            $this->Session->setFlash('Memo is verwijderd.');

            return $this->redirect($form->get('referer')->getData());
        }

        $this->set('hsMemo', $hsMemo);
        $this->set('form', $form->createView());
    }
}
