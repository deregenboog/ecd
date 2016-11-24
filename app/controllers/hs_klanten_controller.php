<?php

use AppBundle\Entity\Klant;
use HsBundle\Entity\HsKlant;
use HsBundle\Form\HsKlantType;
use HsBundle\Entity\HsProfielCode;
use HsBundle\Form\HsProfielCodeType;
use AppBundle\Form\KlantFilterType;
use HsBundle\Form\HsKlantSelectType;

class HsKlantenController extends AppController
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
        $repository = $entityManager->getRepository(HsKlant::class);
        $this->set('klanten', $repository->findAll());
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $hsKlant = $entityManager->find(HsKlant::class, $id);
        $this->set('hsKlant', $hsKlant);
    }

    public function add($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($klantId) {
            $klant = new Klant();
            if ($klantId !== 'new') {
                $klant = $entityManager->find(Klant::class, $klantId);
            }

            $hsKlant = new HsKlant();
            $hsKlant->setKlant($klant);

            $creationForm = $this->createForm(HsKlantType::class, $hsKlant);
            $creationForm->handleRequest($this->request);

            if ($creationForm->isValid()) {
                $entityManager->persist($hsKlant);
                $entityManager->flush();

                $this->Session->setFlash('Klant is opgeslagen.');

                return $this->redirect(array('action' => 'view', $hsKlant->getId()));
            }

            $this->set('creationForm', $creationForm->createView());

            return;
        }

        $filterForm = $this->createForm(KlantFilterType::class)->remove('stadsdeel');
        $filterForm->handleRequest($this->request);

        $selectionForm = $this->createForm(HsKlantSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->request);

        if ($filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isValid()) {
            $hsKlant = $selectionForm->getData();
            if ($hsKlant->getKlant() instanceof Klant) {
                return $this->redirect(['action' => 'add', $hsKlant->getKlant()->getId()]);
            }
            return $this->redirect(['action' => 'add', 'new']);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $hsKlant = $entityManager->find(HsKlant::class, $id);

        $form = $this->createForm(HsKlantType::class, $hsKlant);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Klant is opgeslagen.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('form', $form->createView());
        $this->set('hsKlant', $hsKlant);
    }

    public function deleten($id)
    {
        $form = $this->createForm(HsKlantType::class, new HsBundle\Entity\HsKlant());
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            return $this->redirect(['action' => 'add2']);
        }

        $this->set('form', $form->createView());
    }

    public function add_hs_profiel_code($hsKlantId)
    {
        $hsKlant = $this->getHsKlant($hsKlantId);
        $hsProfielCode = new HsProfielCode();

        $form = $this->createForm(HsProfielCodeType::class, $hsProfielCode);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $hsKlant->addHsProfielCode($hsProfielCode);
            $this->getEntityManager()->flush();

            return $this->redirect(['action' => 'view', $hsKlant->getId()]);
        }

        $this->set('form', $form->createView());
        $this->set('hsKlant', $hsKlant);
    }

    protected function getHsKlant($hsKlantId)
    {
        return $this->getEntityManager()->find(HsKlant::class, $hsKlantId);
    }
}
