<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpHuuraanbod;
use OdpBundle\Entity\OdpHuurovereenkomst;
use OdpBundle\Form\OdpHuuraanbodType;
use OdpBundle\Form\OdpHuuraanbodFilterType;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use OdpBundle\Form\OdpHuurovereenkomstType;

class OdpHuuraanbiedingenController extends AppController
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
        'klant' => ['naam', 'stadsdeel'],
        'startdatum',
        'einddatum',
        'openstaand',
    ];

    private $sortFieldWhitelist = [
        'odpHuuraanbod.id',
        'klant.achternaam',
        'klant.werkgebied',
        'odpHuuraanbod.startdatum',
        'odpHuuraanbod.einddatum',
    ];

    public function index()
    {
        $filter = $this->createForm(OdpHuuraanbodFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OdpHuuraanbod::class);

        $builder = $repository->createQueryBuilder('odpHuuraanbod')
            ->leftJoin('odpHuuraanbod.odpHuurovereenkomst', 'odpHuurovereenkomst')
            ->innerJoin('odpHuuraanbod.odpVerhuurder', 'odpVerhuurder')
            ->innerJoin('odpVerhuurder.klant', 'klant');

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'klant.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $odpHuuraanbod = $this->getEntityManager()->find(OdpHuuraanbod::class, $id);
        $this->set('odpHuuraanbod', $odpHuuraanbod);
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuuraanbod = $entityManager->find(OdpHuuraanbod::class, $id);

        $form = $this->createForm(OdpHuuraanbodType::class, $odpHuuraanbod);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $entityManager->flush();

                $this->Session->setFlash('Klant is opgeslagen.');

                return $this->redirect(array('action' => 'index'));
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('odpHuuraanbod', $odpHuuraanbod);
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuuraanbod = $entityManager->find(OdpHuuraanbod::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($odpHuuraanbod);
            $entityManager->flush();

            $this->Session->setFlash('Klant is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('odpHuuraanbod', $odpHuuraanbod);
        $this->set('form', $form->createView());
    }

    public function maak_huurovereenkomst($huuraanbodId)
    {
        /** @var OdpHuuraanbod $odpHuuraanbod */
        /** @var Medewerker $medewerker */
        $entityManager = $this->getEntityManager();
        $odpHuuraanbod = $entityManager->find(OdpHuuraanbod::class, $huuraanbodId);
        $medewerker = $this->getMedewerker();

        $odpHuurovereenkomst = new OdpHuurovereenkomst();
        $odpHuurovereenkomst->setOdpHuuraanbod($odpHuuraanbod);
        $odpHuurovereenkomst->setMedewerker($medewerker);

        $form = $this->createForm(OdpHuurovereenkomstType::class, $odpHuurovereenkomst);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $entityManager->persist($odpHuurovereenkomst);
                $entityManager->flush();

                $this->Session->setFlash('Huurovereenkomst is opgeslagen.');

                return $this->redirect(array('action' => 'view', $huuraanbodId));
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('odpHuurovereenkomst', $odpHuurovereenkomst);

        $this->render('/odp_huurovereenkomsten/add');
    }
}
