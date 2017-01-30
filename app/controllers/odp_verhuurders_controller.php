<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpVerhuurder;
use OdpBundle\Form\OdpVerhuurderType;
use AppBundle\Form\KlantFilterType;
use OdpBundle\Form\OdpVerhuurderSelectType;
use Doctrine\DBAL\Driver\PDOException;
use OdpBundle\Form\OdpVerhuurderFilterType;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use OdpBundle\Entity\HsMemo;
use OdpBundle\Form\HsMemoType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OdpVerhuurdersController extends AppController
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
    ];

    private $sortFieldWhitelist = [
        'OdpVerhuurder.id',
        'klant.achternaam',
        'klant.werkgebied',
    ];

    public function index()
    {
        $filter = $this->createForm(OdpVerhuurderFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OdpVerhuurder::class);

        $builder = $repository->createQueryBuilder('odpVerhuurder')
            ->innerJoin('odpVerhuurder.klant', 'klant')
            ->andWhere('klant.disabled = false')
        ;

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
        $entityManager = $this->getEntityManager();
        $odpVerhuurder = $entityManager->find(OdpVerhuurder::class, $id);
        $this->set('odpVerhuurder', $odpVerhuurder);
    }

    public function add($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($klantId) {
            $klant = new Klant();
            if ($klantId !== 'new') {
                $klant = $entityManager->find(Klant::class, $klantId);
            }

            $odpVerhuurder = new OdpVerhuurder();
            $odpVerhuurder->setKlant($klant);

            $creationForm = $this->createForm(OdpVerhuurderType::class, $odpVerhuurder);
            $creationForm->add('memo', TextareaType::class, [
                'label' => 'Intake-memo',
                'mapped' => false,
                'attr' => ['rows' => 10, 'cols' => 80],
            ]);
            $creationForm->handleRequest($this->request);

            if ($creationForm->isValid()) {
                try {
                    $hsMemo = new HsMemo($odpVerhuurder->getKlant()->getMedewerker());
                    $hsMemo->setMemo($creationForm->get('memo')->getData());
                    $odpVerhuurder->addHsMemo($hsMemo);

                    $entityManager->persist($odpVerhuurder);
                    $entityManager->flush();

                    $this->Session->setFlash('Klant is opgeslagen.');

                    return $this->redirect(array('action' => 'view', $odpVerhuurder->getId()));
                } catch (\Exception $e) {
                    if ($e->getPrevious() instanceof PDOException && $e->getPrevious()->getCode() == 23000) {
                        $this->Session->setFlash('Deze klant heeft al een Homeservice-dossier.');
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

        $selectionForm = $this->createForm(OdpVerhuurderSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->request);

        if ($filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isValid()) {
            $odpVerhuurder = $selectionForm->getData();
            if ($odpVerhuurder->getKlant() instanceof Klant) {
                return $this->redirect(['action' => 'add', $odpVerhuurder->getKlant()->getId()]);
            }

            return $this->redirect(['action' => 'add', 'new']);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $odpVerhuurder = $entityManager->find(OdpVerhuurder::class, $id);

        $form = $this->createForm(OdpVerhuurderType::class, $odpVerhuurder);
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
        $this->set('odpVerhuurder', $odpVerhuurder);
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $odpVerhuurder = $entityManager->find(OdpVerhuurder::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($odpVerhuurder);
            $entityManager->flush();

            $this->Session->setFlash('Klant is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('odpVerhuurder', $odpVerhuurder);
        $this->set('form', $form->createView());
    }
}
