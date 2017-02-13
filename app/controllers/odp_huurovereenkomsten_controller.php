<?php

use AppBundle\Entity\Klant;
use AppBundle\Form\ConfirmationType;
use OdpBundle\Entity\OdpHuurder;
use OdpBundle\Entity\OdpHuurovereenkomst;
use OdpBundle\Form\OdpHuurovereenkomstAfsluitingType;
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
        'startdatum',
        'einddatum',
    ];

    private $sortFieldWhitelist = [
        'odpHuurovereenkomst.id',
        'odpHuurderKlant.achternaam',
        'odpVerhuurderKlant.achternaam',
        'odpHuurovereenkomst.startdatum',
        'odpHuurovereenkomst.einddatum',
    ];

    public function index()
    {
        $filter = $this->createForm(OdpHuurovereenkomstFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OdpHuurovereenkomst::class);

        $builder = $repository->createQueryBuilder('odpHuurovereenkomst')
            ->innerJoin('odpHuurovereenkomst.odpHuurverzoek', 'odpHuurverzoek')
            ->innerJoin('odpHuurovereenkomst.odpHuuraanbod', 'odpHuuraanbod')
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

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
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

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurovereenkomst = $entityManager->find(OdpHuurovereenkomst::class, $id);

        $form = $this->createForm(OdpHuurovereenkomstType::class, $odpHuurovereenkomst);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $entityManager->flush();

                $this->Session->setFlash('Huurovereenkomst is opgeslagen.');

                return $this->redirect(array('action' => 'index'));
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('odpHuurovereenkomst', $odpHuurovereenkomst);
    }

    public function afsluiten($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurovereenkomst = $entityManager->find(OdpHuurovereenkomst::class, $id);

        $form = $this->createForm(OdpHuurovereenkomstAfsluitingType::class, $odpHuurovereenkomst);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $entityManager->flush();

                $this->Session->setFlash('Huurovereenkomst is afgesloten.');

                return $this->redirect(array('action' => 'index'));
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('odpHuurovereenkomst', $odpHuurovereenkomst);
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurovereenkomst = $entityManager->find(OdpHuurovereenkomst::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($odpHuurovereenkomst);
            $entityManager->flush();

            $this->Session->setFlash('Huurovereenkomst is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('odpHuurovereenkomst', $odpHuurovereenkomst);
        $this->set('form', $form->createView());
    }
}
