<?php

use HsBundle\Entity\HsDeclaratieCategorie;
use HsBundle\Form\HsDeclaratieCategorieType;
use AppBundle\Form\ConfirmationType;

class HsDeclaratieCategorieenController extends AppController
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
        'hsDeclaratieCategorie.id',
        'hsDeclaratieCategorie.naam',
    ];

    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsDeclaratieCategorie::class);

        $builder = $repository->createQueryBuilder('hsDeclaratieCategorie');

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'hsDeclaratieCategorie.naam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $HsDeclaratieCategorie = $entityManager->find(HsDeclaratieCategorie::class, $id);
        $this->set('HsDeclaratieCategorie', $HsDeclaratieCategorie);
    }

    public function add()
    {
        $hsDeclaratieCategorie = new HsDeclaratieCategorie();

        $form = $this->createForm(HsDeclaratieCategorieType::class, $hsDeclaratieCategorie);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager = $this->getEntityManager();
            $entityManager->persist($hsDeclaratieCategorie);
            $entityManager->flush();

            $this->Session->setFlash('Declaratiecategorie is opgeslagen.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsDeclaratieCategorie::class);
        $hsDeclaratieCategorie = $repository->find($id);

        $form = $this->createForm(HsDeclaratieCategorieType::class, $hsDeclaratieCategorie);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager->persist($hsDeclaratieCategorie);
            $entityManager->flush();

            $this->Session->setFlash('Declaratiecategorie is opgeslagen.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('form', $form->createView());
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsDeclaratieCategorie::class);
        $hsDeclaratieCategorie = $repository->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager->remove($hsDeclaratieCategorie);
            $entityManager->flush();

            $this->Session->setFlash('Declaratiecategorie is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('hsDeclaratieCategorie', $hsDeclaratieCategorie);
        $this->set('form', $form->createView());
    }
}
