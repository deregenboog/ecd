<?php

use HsBundle\Entity\HsFactuur;
use App\Form\HsFactuurType;
use App\Form\ConfirmationType;

class HsFacturenController extends AppController
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
        $repository = $entityManager->getRepository(HsFactuur::class);
        $this->set('hsFacturen', $repository->findAll());
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $hsFactuur = $entityManager->find(HsFactuur::class, $id);
        $this->set('hsFactuur', $hsFactuur);
    }

    public function add()
    {
        $hsFactuur = new HsFactuur();

        $form = $this->createForm(HsFactuurType::class, $hsFactuur);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager = $this->getEntityManager();
            $entityManager->persist($hsFactuur);
            $entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $hsFactuur = $entityManager->find(HsFactuur::class, $id);

        $form = $this->createForm(HsFactuurType::class, $hsFactuur);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager = $this->getEntityManager();
            $entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $hsFactuur = $entityManager->find(HsFactuur::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($hsFactuur);
            $entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
        $this->set('hsFactuur', $hsFactuur);
    }
}
