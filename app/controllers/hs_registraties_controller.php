<?php

use AppBundle\Entity\Klant;
use HsBundle\Entity\HsRegistratie;
use HsBundle\Entity\HsKlus;
use HsBundle\Entity\HsVrijwilliger;
use HsBundle\Form\HsRegistratieType;
use AppBundle\Entity\Medewerker;

class HsRegistratiesController extends AppController
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
        $repository = $entityManager->getRepository('HsBundle\Entity\HsKlant');
        $this->set('klanten', $repository->findAll());
    }

    public function add($hsKlusId, $hsVrijwilligerId = -1)
    {
        $entityManager = $this->getEntityManager();
        $hsKlus = $entityManager->find(HsKlus::class, $hsKlusId);
        $hsVrijwilliger = $entityManager->find(HsVrijwilliger::class, $hsVrijwilligerId);

        $medewerkerId = $this->Session->read('Auth.Medewerker.id');
        $medewerker = $this->getEntityManager()->find(Medewerker::class, $medewerkerId);

        $form = $this->createForm(HsRegistratieType::class, new HsRegistratie($hsKlus, $hsVrijwilliger, $medewerker));
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirect([
                'controller' => 'hs_klussen',
                'action' => 'view',
                $hsKlus->getId(),
            ]);
        }

        $this->set('hsKlus', $hsKlus);
        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $hsRegistratie = $entityManager->find(HsRegistratie::class, $id);

        $form = $this->createForm(HsRegistratieType::class, $hsRegistratie);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager->flush();

            return $this->redirect([
                'controller' => 'hs_klussen',
                'action' => 'view',
                $hsRegistratie->getHsKlus()->getId(),
            ]);
        }

        $this->set('form', $form->createView());
    }

//     public function view($id)
//     {
//         $klant = $this->HsKlant->findById($id);
//         $this->set('klant', $klant);
//     }
}
