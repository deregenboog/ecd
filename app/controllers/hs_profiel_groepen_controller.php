<?php

use HsBundle\Entity\HsProfielGroep;
use App\Form\HsProfielGroepType;
use App\Form\ConfirmationType;

class HsProfielGroepenController extends AppController
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
        $repository = $entityManager->getRepository(HsProfielGroep::class);
        $this->set('hsProfielGroepen', $repository->findAll());
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $hsProfielGroep = $entityManager->find(HsProfielGroep::class, $id);
        $this->set('hsProfielGroep', $hsProfielGroep);
    }

    public function add()
    {
        $hsProfielGroep = new HsProfielGroep();

        $form = $this->createForm(HsProfielGroepType::class, $hsProfielGroep);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager = $this->getEntityManager();
            $entityManager->persist($hsProfielGroep);
            $entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $hsProfielGroep = $entityManager->find(HsProfielGroep::class, $id);

        $form = $this->createForm(HsProfielGroepType::class, $hsProfielGroep);
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
        $hsProfielGroep = $entityManager->find(HsProfielGroep::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($hsProfielGroep);
            $entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
        $this->set('hsProfielGroep', $hsProfielGroep);
    }
}
