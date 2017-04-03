<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpHuurder;
use OdpBundle\Entity\OdpHuurovereenkomst;
use OdpBundle\Form\OdpHuurovereenkomstFilterType;
use OdpBundle\Form\OdpHuurovereenkomstType;

class OdpHuurovereenkomstenController extends AppController
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
        'id',
        'odpHuurderKlant' => ['naam'],
        'odpVerhuurderKlant' => ['naam'],
        'medewerker',
        'startdatum',
        'opzegdatum',
        'einddatum',
    ];

    private $sortFieldWhitelist = [
        'odpHuurovereenkomst.id',
        'odpHuurderKlant.achternaam',
        'odpVerhuurderKlant.achternaam',
        'medewerker.achternaam',
        'odpHuurovereenkomst.startdatum',
        'odpHuurovereenkomst.opzegdatum',
        'odpHuurovereenkomst.einddatum',
    ];

    public function index()
    {
        $filter = $this->createForm(OdpHuurovereenkomstFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OdpHuurovereenkomst::class);

        $builder = $repository->createQueryBuilder('odpHuurovereenkomst')
            ->innerJoin('odpHuurovereenkomst.odpHuurverzoek', 'odpHuurverzoek')
            ->innerJoin('odpHuurovereenkomst.odpHuuraanbod', 'odpHuuraanbod')
            ->innerJoin('odpHuurovereenkomst.medewerker', 'medewerker')
            ->innerJoin('odpHuurverzoek.odpHuurder', 'odpHuurder')
            ->innerJoin('odpHuuraanbod.odpVerhuurder', 'odpVerhuurder')
            ->innerJoin('odpHuurder.klant', 'odpHuurderKlant')
            ->innerJoin('odpVerhuurder.klant', 'odpVerhuurderKlant')
            ->andWhere('odpHuurderKlant.disabled = false')
            ->andWhere('odpVerhuurderKlant.disabled = false')
        ;

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'odpHuurovereenkomst.id',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $odpHuurovereenkomst = $this->getEntityManager()->find(OdpHuurovereenkomst::class, $id);
        $this->set('odpHuurovereenkomst', $odpHuurovereenkomst);
    }

    public function edit($odpHuurovereenkomstId)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurovereenkomst = $entityManager->find(OdpHuurovereenkomst::class, $odpHuurovereenkomstId);

        $form = $this->createForm(OdpHuurovereenkomstType::class, $odpHuurovereenkomst);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->flashError('Er is een fout opgetreden.');
            }

            return $this->redirect(['controller' => 'odp_huurovereenkomsten', 'action' => 'view', $odpHuurovereenkomst->getId()]);
        }

        $this->set('form', $form->createView());
    }
}
