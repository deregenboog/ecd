<?php

use App\Entity\HsKlus;
use App\Form\HsKlusType;
use App\Entity\HsKlant;
use App\Entity\HsFactuur;

class HsKlussenController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsKlus::class);
        $this->set('hsKlussen', $repository->findAll());
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsKlus::class);
        $this->set('hsKlus', $repository->find($id));
    }

    public function add($hsKlantId)
    {
        $entityManager = $this->getEntityManager();
        $hsKlant = $entityManager->find(HsKlant::class, $hsKlantId);
        $hsKlus = new HsKlus($hsKlant);

        $form = $this->createForm(HsKlusType::class, $hsKlus);
        $form->handleRequest();

        if ($form->isValid()) {
            $entityManager->persist($hsKlus);
            $entityManager->flush();

            $this->Session->setFlash('Klus is opgeslagen.');

            return $this->redirect(array('action' => 'view', $hsKlus->getId()));
        }

        $this->set('form', $form->createView());
        $this->set('hsKlant', $hsKlant);
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsKlus::class);
        $hsKlus = $repository->find($id);

        $form = $this->createForm(HsKlusType::class, $hsKlus);
        $form->handleRequest();

        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Klus is opgeslagen.');

            return $this->redirect(array('action' => 'view', $hsKlus->getId()));
        }

        $this->set('form', $form->createView());
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('App\Entity\HsActiviteit');
        $activiteit = $repository->find($id);
        $this->set('activiteit', $activiteit);

        $form = $this->createForm('App\Form\ConfirmationType');
        $form->handleRequest();

        if ($form->isValid()) {
            $entityManager->remove($activiteit);
            $entityManager->flush();

            $this->Session->setFlash('Activiteit is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('form', $form->createView());
    }

    public function add_hs_vrijwilliger($id)
    {
        $entityManager = $this->getEntityManager();
        $hsKlus = $entityManager->find(HsKlus::class, $id);

        $form = $this->createForm(HsKlusType::class, $hsKlus, [
            'mode' => HsKlusType::MODE_ADD_VRIJWILLIGER,
        ]);
        $form->handleRequest();

        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Vrijwilliger is toegevoegd.');

            return $this->redirect(array('action' => 'view', $hsKlus->getId()));
        }

        $this->set('form', $form->createView());
        $this->set('hsKlus', $hsKlus);
    }

    public function add_hs_factuur($id)
    {
        $entityManager = $this->getEntityManager();
        $hsKlus = $entityManager->find(HsKlus::class, $id);

        $hsFactuur = new HsFactuur($hsKlus);
        if (count($hsFactuur->getHsRegistraties()) > 0) {
            $entityManager->persist($hsFactuur);
            $entityManager->flush();

            $this->Session->setFlash('Factuur is toegevoegd.');
        } else {
            $this->Session->setFlash('Er is niks te factureren.');
        }

        return $this->redirect(array('action' => 'view', $hsKlus->getId()));
    }
}
