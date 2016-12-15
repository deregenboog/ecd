<?php

use AppBundle\Entity\Klant;
use HsBundle\Entity\HsKlant;
use HsBundle\Form\HsKlantType;
use AppBundle\Form\KlantFilterType;
use HsBundle\Form\HsKlantSelectType;
use Doctrine\DBAL\Driver\PDOException;
use HsBundle\Form\HsKlantFilterType;
use AppBundle\Form\ConfirmationType;

class HsKlantenController extends AppController
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
        'openstaand',
        'klant' => ['naam', 'stadsdeel'],
    ];

    private $sortFieldWhitelist = [
        'hsKlant.id',
        'klant.achternaam',
        'klant.werkgebied',
    ];

    public function index()
    {
        $filter = $this->createForm(HsKlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsKlant::class);

        $builder = $repository->createQueryBuilder('hsKlant')
            ->innerJoin('hsKlant.klant', 'klant')
            ->andWhere('klant.disabled = false')
        ;

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'klant.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
            'wrap-queries' => true, // because of HAVING clause in filter
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $hsKlant = $entityManager->find(HsKlant::class, $id);
        $this->set('hsKlant', $hsKlant);
    }

    public function add($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($klantId) {
            $klant = new Klant();
            if ($klantId !== 'new') {
                $klant = $entityManager->find(Klant::class, $klantId);
            }

            $hsKlant = new HsKlant();
            $hsKlant->setKlant($klant);

            $creationForm = $this->createForm(HsKlantType::class, $hsKlant);
            $creationForm->handleRequest($this->request);

            if ($creationForm->isValid()) {
                try {
                    $entityManager->persist($hsKlant);
                    $entityManager->flush();

                    $this->Session->setFlash('Klant is opgeslagen.');

                    return $this->redirect(array('action' => 'view', $hsKlant->getId()));
                } catch (\Exception $e) {
                    if ($e->getPrevious() instanceof PDOException && $e->getPrevious()->getCode() == 23000) {
                        $this->Session->setFlash('Deze vrijwilliger heeft al een Homeservice-dossier.');
                    } else {
                        $this->Session->setFlash('Er is een fout opgetreden.');
                    }
                } finally {
                    return $this->redirect(array('action' => 'index'));
                }
            }

            $this->set('creationForm', $creationForm->createView());

            return;
        }

        $filterForm = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($this->request);

        $selectionForm = $this->createForm(HsKlantSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->request);

        if ($filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isValid()) {
            $hsKlant = $selectionForm->getData();
            if ($hsKlant->getKlant() instanceof Klant) {
                return $this->redirect(['action' => 'add', $hsKlant->getKlant()->getId()]);
            }

            return $this->redirect(['action' => 'add', 'new']);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $hsKlant = $entityManager->find(HsKlant::class, $id);

        $form = $this->createForm(HsKlantType::class, $hsKlant);
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
        $this->set('hsKlant', $hsKlant);
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $hsKlant = $entityManager->find(HsKlant::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($hsKlant);
            $entityManager->flush();

            $this->Session->setFlash('Klant is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('hsKlant', $hsKlant);
        $this->set('form', $form->createView());
    }
}
