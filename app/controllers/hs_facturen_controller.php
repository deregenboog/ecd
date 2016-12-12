<?php

use HsBundle\Entity\HsFactuur;
use App\Form\HsFactuurType;
use AppBundle\Form\ConfirmationType;
use HsBundle\Entity\HsKlus;
use HsBundle\Form\HsFactuurFilterType;

class HsFacturenController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';


    private $enabledFilters = [
        'nummer',
        'datum',
        'bedrag',
        'klant' => ['naam'],
    ];

    private $sortFieldWhitelist = [
        'hsFactuur.nummer',
        'hsFactuur.datum',
        'hsFactuur.bedrag',
        'klant.achternaam',
    ];

    public function index()
    {
        $filter = $this->createForm(HsFactuurFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsFactuur::class);

        $builder = $repository->createQueryBuilder('hsFactuur')
            ->innerJoin('hsFactuur.hsKlus', 'hsKlus')
            ->innerJoin('hsKlus.hsKlant', 'hsKlant')
            ->innerJoin('hsKlant.klant', 'klant')
        ;

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'hsFactuur.nummer',
            'defaultSortDirection' => 'desc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $hsFactuur = $entityManager->find(HsFactuur::class, $id);
        $this->set('hsFactuur', $hsFactuur);
    }

    public function add($hsKlusId)
    {
        $entityManager = $this->getEntityManager();
        $hsKlus = $entityManager->find(HsKlus::class, $hsKlusId);

        $hsFactuur = new HsFactuur($hsKlus);
        if (count($hsFactuur->getHsRegistraties()) > 0) {
            $entityManager->persist($hsFactuur);
            $entityManager->flush();

            $this->Session->setFlash('Factuur is toegevoegd.');
        } else {
            $this->Session->setFlash('Er is voor de vorige maand niks te factureren.');
        }

        return $this->redirect([
            'controller' => 'hs_klussen',
            'action' => 'view',
            $hsKlus->getId(),
        ]);
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $hsFactuur = $entityManager->find(HsFactuur::class, $id);

        $form = $this->createForm(HsFactuurType::class, $hsFactuur);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager = $this->getEntityManager();
            $entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $hsFactuur = $entityManager->find(HsFactuur::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($hsFactuur);
            $entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
        $this->set('hsFactuur', $hsFactuur);
    }
}
