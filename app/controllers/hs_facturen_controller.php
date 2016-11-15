<?php

use App\Entity\HsFactuur;
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
        $entityManager = Registry::getInstance()->getManager();
        $repository = $entityManager->getRepository(HsFactuur::class);
        $this->set('hsFacturen', $repository->findAll());
    }

    public function view($id)
    {
        $entityManager = Registry::getInstance()->getManager();
        $hsFactuur = $entityManager->find(HsFactuur::class, $id);
        $this->set('hsFactuur', $hsFactuur);
    }

    public function add()
    {
        $hsFactuur = new HsFactuur();

        $form = $this->createForm(HsFactuurType::class, $hsFactuur);
        $form->handleRequest();

        if ($form->isValid()) {
            $entityManager = Registry::getInstance()->getManager();
            $entityManager->persist($hsFactuur);
            $entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = Registry::getInstance()->getManager();
        $hsFactuur = $entityManager->find(HsFactuur::class, $id);

        $form = $this->createForm(HsFactuurType::class, $hsFactuur);
        $form->handleRequest();

        if ($form->isValid()) {
            $entityManager = Registry::getInstance()->getManager();
            $entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }

    public function delete($id)
    {
        $entityManager = Registry::getInstance()->getManager();
        $hsFactuur = $entityManager->find(HsFactuur::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest();

        if ($form->isValid()) {
            $entityManager->remove($hsFactuur);
            $entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
        $this->set('hsFactuur', $hsFactuur);
    }
}
