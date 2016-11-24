<?php

use App\Entity\Klant;
use HsBundle\Entity\HsRegistratie;
use App\Form\HsRegistratieType;
use HsBundle\Entity\HsKlus;
use HsBundle\Entity\HsVrijwilliger;

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

    public function add($hsKlusId, $hsVrijwilligerId)
    {
        $entityManager = $this->getEntityManager();
        $hsKlus = $entityManager->find(HsKlus::class, $hsKlusId);
        $hsVrijwilliger = $entityManager->find(HsVrijwilliger::class, $hsVrijwilligerId);

        $form = $this->createForm(HsRegistratieType::class, new HsRegistratie($hsKlus, $hsVrijwilliger));
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirect([
                'controller' => 'hs_klussen',
                'action' => 'view',
                $hsKlus->getId(),
            ]);
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $hsRegistratie = $entityManager->find(HsRegistratie::class, $id);

        $form = $this->createForm(HsRegistratieType::class, $hsRegistratie);
        $form->handleRequest($this->request);

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
