<?php

use OekBundle\Entity\OekGroep;
use OekBundle\Form\Model\OekGroepModel;
use OekBundle\Form\OekGroepKlantType;
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
        'oekGroep.naam'
    ];

    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekGroep::class);

        $builder = $repository->createQueryBuilder('oekGroep')
            ->leftJoin('oekGroep.oekKlanten', 'oekKlanten')
            ->leftJoin('oekGroep.oekTrainingen', 'oekTrainingen');

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'oekGroep.naam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekGroep::class);
        $this->set('oekGroep', $repository->find($id));
    }

    public function add()
    {
        $entityManager = $this->getEntityManager();

        $oekGroep = new OekGroep();

        $form = $this->createForm(OekGroepType::class, $oekGroep);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->persist($oekGroep);
            $entityManager->flush();

            $this->Session->setFlash('Groep is opgeslagen.');

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
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Groep is opgeslagen.');

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
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($oekGroep);
            $entityManager->flush();

            $this->Session->setFlash('Groep is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('oekGroep', $oekGroep);
        $this->set('form', $form->createView());
    }

    public function add_klant($oekGroepId)
    {
        /** @var OekGroep $oekGroep */
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekGroep::class);
        $oekGroep = $repository->find($oekGroepId);
        $oekGroepModel = new OekGroepModel($oekGroep);

        $form = $this->createForm(OekGroepKlantType::class, $oekGroepModel);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Klant is toegevoegd aan groep.');

            return $this->redirect(array('action' => 'view', $oekGroep->getId()));
        }

        $this->set('form', $form->createView());
    }
}
