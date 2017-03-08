<?php

use OekBundle\Form\OekVerwijzingType;
use OekBundle\Entity\OekVerwijzingNaar;

class OekVerwijzingenNaarController extends AppController
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
        'oekVerwijzing.id',
        'oekVerwijzing.naam',
    ];

    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(OekVerwijzingNaar::class);

        $builder = $repository->createQueryBuilder('oekVerwijzing');

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'oekVerwijzing.naam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('pagination', $pagination);
    }

    public function add()
    {
        $oekVerwijzing = new OekVerwijzingNaar();

        $form = $this->createForm(OekVerwijzingType::class, $oekVerwijzing);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager = $this->getEntityManager();
            $entityManager->persist($oekVerwijzing);
            $entityManager->flush();

            $this->Session->setFlash('Verwijzing is aangemaakt.');

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $oekVerwijzing = $entityManager->find(OekVerwijzingNaar::class, $id);

        $form = $this->createForm(OekVerwijzingType::class, $oekVerwijzing);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Verwijzing is gewijzigd.');

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }
}
