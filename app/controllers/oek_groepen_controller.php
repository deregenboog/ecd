<?php

use OekBundle\Entity\OekGroep;
use OekBundle\Form\OekGroepType;
use AppBundle\Form\ConfirmationType;

class OekGroepenController extends AppController
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
        'oekGroep.id',
        'oekGroep.naam',
    ];

    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekGroep::class);

        $builder = $repository->createQueryBuilder('oekGroep')
            ->select('oekGroep, oekLidmaatschap, oekKlant, oekTraining')
            ->leftJoin('oekGroep.oekLidmaatschappen', 'oekLidmaatschap')
            ->leftJoin('oekLidmaatschap.oekKlant', 'oekKlant')
            ->leftJoin('oekGroep.oekTrainingen', 'oekTraining');

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'oekGroep.naam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $oekGroep = $this->getEntityManager()->find(OekGroep::class, $id);
        $this->set('oekGroep', $oekGroep);
    }

    public function add()
    {
        $entityManager = $this->getEntityManager();

        $oekGroep = new OekGroep();

        $form = $this->createForm(OekGroepType::class, $oekGroep);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager->persist($oekGroep);
            $entityManager->flush();

            $this->Session->setFlash('Groep is aangemaakt.');

            return $this->redirect(array('action' => 'view', $oekGroep->getId()));
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekGroep::class);
        $oekGroep = $repository->find($id);

        $form = $this->createForm(OekGroepType::class, $oekGroep);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Groep is gewijzigd.');

            return $this->redirect(array('action' => 'view', $oekGroep->getId()));
        }

        $this->set('form', $form->createView());
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekGroep::class);
        $oekGroep = $repository->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager->remove($oekGroep);
            $entityManager->flush();

            $this->Session->setFlash('Groep is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('oekGroep', $oekGroep);
        $this->set('form', $form->createView());
    }
}
