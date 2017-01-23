<?php

use HsBundle\Entity\HsFactuur;
use App\Form\HsFactuurType;
use AppBundle\Form\ConfirmationType;
use HsBundle\Entity\HsKlus;
use HsBundle\Form\HsFactuurFilterType;
use HsBundle\Entity\HsBetaling;
use HsBundle\Form\HsBetalingFilterType;
use HsBundle\Form\HsBetalingType;

class HsBetalingenController extends AppController
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
        'referentie',
        'datum',
        'bedrag',
        'hsFactuur' => ['nummer'],
        'klant' => ['naam'],
    ];

    private $sortFieldWhitelist = [
        'hsFactuur.nummer',
        'hsBetaling.referentie',
        'hsBetaling.datum',
        'hsBetaling.bedrag',
        'hsFactuur.nummer',
    ];

    public function index()
    {
        $filter = $this->createForm(HsBetalingFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsBetaling::class);

        $builder = $repository->createQueryBuilder('hsBetaling')
            ->innerJoin('hsBetaling.hsFactuur', 'hsFactuur')
            ->innerJoin('hsFactuur.hsKlus', 'hsKlus')
            ->innerJoin('hsKlus.hsKlant', 'hsKlant')
            ->innerJoin('hsKlant.klant', 'klant')
        ;

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'hsBetaling.datum',
            'defaultSortDirection' => 'desc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function add($hsFactuurId)
    {
        $entityManager = $this->getEntityManager();
        $hsFactuur = $entityManager->find(HsFactuur::class, $hsFactuurId);

        $hsBetaling = new HsBetaling($hsFactuur);

        $form = $this->createForm(HsBetalingType::class, $hsBetaling);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();

            $this->Session->setFlash('Betaling is toegevoegd.');

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $hsBetaling = $entityManager->find(HsBetaling::class, $id);

        $form = $this->createForm(HsBetalingType::class, $hsBetaling);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Betaling is bijgewerkt.');

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }

//     public function delete($id)
//     {
//         $entityManager = $this->getEntityManager();
//         $hsFactuur = $entityManager->find(HsFactuur::class, $id);

//         $form = $this->createForm(ConfirmationType::class);
//         $form->handleRequest($this->request);

//         if ($form->isValid()) {
//             $entityManager->remove($hsFactuur);
//             $entityManager->flush();

//             return $this->redirect(['action' => 'index']);
//         }

//         $this->set('form', $form->createView());
//         $this->set('hsFactuur', $hsFactuur);
//     }
}
