<?php

use AppBundle\Entity\Klant;
use HsBundle\Entity\HsRegistratie;
use HsBundle\Entity\HsKlus;
use HsBundle\Form\HsRegistratieType;
use HsBundle\Entity\HsDeclaratie;
use HsBundle\Form\HsDeclaratieType;

class HsDeclaratiesController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    public function add($hsKlusId)
    {
        $entityManager = $this->getEntityManager();
        $hsKlus = $entityManager->find(HsKlus::class, $hsKlusId);

        $form = $this->createForm(HsDeclaratieType::class, new HsDeclaratie($hsKlus));
        $form->handleRequest($this->request);

//         if ($form->isValid()) {
//             $entityManager->persist($form->getData());
//             $entityManager->flush();

//             return $this->redirect([
//                 'controller' => 'hs_klussen',
//                 'action' => 'view',
//                 $hsKlus->getId(),
//             ]);
//         }

        $this->set('hsKlus', $hsKlus);
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
