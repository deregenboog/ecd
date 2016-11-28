<?php

use HsBundle\Entity\HsKlus;
use HsBundle\Form\HsKlusType;
use HsBundle\Entity\HsKlant;
use HsBundle\Entity\HsFactuur;
use AppBundle\Form\ConfirmationType;

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

    private $sortFieldWhitelist = [
        'hsKlus.id',
        'hsKlus.datum',
        'klant.achternaam',
        'klant.werkgebied',
        'hsActiviteit.naam',
        'vrijwilliger.achternaam',
    ];

    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsKlus::class);

        $builder = $repository->createQueryBuilder('hsKlus')
            ->innerJoin('hsKlus.hsKlant', 'hsKlant')
            ->innerJoin('hsKlus.hsActiviteit', 'hsActiviteit')
            ->innerJoin('hsKlant.klant', 'klant');

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'hsKlus.datum',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('pagination', $pagination);
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
        $form->handleRequest($this->request);

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
        $form->handleRequest($this->request);

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
        $repository = $entityManager->getRepository(HsKlus::class);
        $hsKlus = $repository->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($hsKlus);
            $entityManager->flush();

            $this->Session->setFlash('Klus is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('hsKlus', $hsKlus);
        $this->set('form', $form->createView());
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
