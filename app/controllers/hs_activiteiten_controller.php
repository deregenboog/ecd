<?php

use HsBundle\Entity\HsActiviteit;
use HsBundle\Form\HsActiviteitType;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class HsActiviteitenController extends AppController
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
        'hsActiviteit.id',
        'hsActiviteit.naam',
    ];

    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsActiviteit::class);

        $builder = $repository->createQueryBuilder('hsActiviteit');

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'hsActiviteit.naam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $hsActiviteit = $entityManager->find(HsActiviteit::class, $id);
        $this->set('hsActiviteit', $hsActiviteit);
    }

    public function add()
    {
        $activiteit = new HsActiviteit();

        $form = $this->createForm(HsActiviteitType::class, $activiteit);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager = $this->getEntityManager();
            $entityManager->persist($activiteit);
            $entityManager->flush();

            $this->Session->setFlash('Activiteit is opgeslagen.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsActiviteitType::class);
        $activiteit = $repository->find($id);

        $form = $this->createForm('App\Form\HsActiviteitType', $activiteit);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->persist($activiteit);
            $entityManager->flush();

            $this->Session->setFlash('Activiteit is opgeslagen.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('form', $form->createView());
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsActiviteitType::class);
        $activiteit = $repository->find($id);
        $this->set('activiteit', $activiteit);

        $form = $this->createForm(ConfirmationQuestion::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($activiteit);
            $entityManager->flush();

            $this->Session->setFlash('Activiteit is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('form', $form->createView());
    }
}
