<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpHuurder;
use OdpBundle\Form\OdpHuurderType;
use AppBundle\Form\KlantFilterType;
use OdpBundle\Form\OdpHuurderSelectType;
use Doctrine\DBAL\Driver\PDOException;
use OdpBundle\Form\OdpHuurderFilterType;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use OdpBundle\Entity\HsMemo;
use OdpBundle\Form\HsMemoType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OdpHuurdersController extends AppController
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
        'OdpHuurder.id',
        'klant.achternaam',
        'klant.werkgebied',
    ];

    public function index()
    {
        $filter = $this->createForm(OdpHuurderFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OdpHuurder::class);

        $builder = $repository->createQueryBuilder('odpHuurder')
            ->innerJoin('odpHuurder.klant', 'klant')
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
        $odpHuurder = $entityManager->find(OdpHuurder::class, $id);
        $this->set('odpHuurder', $odpHuurder);
    }

    public function add($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($klantId) {
            $klant = new Klant();
            if ($klantId !== 'new') {
                $klant = $entityManager->find(Klant::class, $klantId);
            }

            $odpHuurder = new OdpHuurder();
            $odpHuurder->setKlant($klant);

            $creationForm = $this->createForm(OdpHuurderType::class, $odpHuurder);
            $creationForm->add('memo', TextareaType::class, [
                'label' => 'Intake-memo',
                'mapped' => false,
                'attr' => ['rows' => 10, 'cols' => 80],
            ]);
            $creationForm->handleRequest($this->request);

            if ($creationForm->isValid()) {
                try {
                    $hsMemo = new HsMemo($odpHuurder->getKlant()->getMedewerker());
                    $hsMemo->setMemo($creationForm->get('memo')->getData());
                    $odpHuurder->addHsMemo($hsMemo);

                    $entityManager->persist($odpHuurder);
                    $entityManager->flush();

                    $this->Session->setFlash('Klant is opgeslagen.');

                    return $this->redirect(array('action' => 'view', $odpHuurder->getId()));
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

        $selectionForm = $this->createForm(OdpHuurderSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->request);

        if ($filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isValid()) {
            $odpHuurder = $selectionForm->getData();
            if ($odpHuurder->getKlant() instanceof Klant) {
                return $this->redirect(['action' => 'add', $odpHuurder->getKlant()->getId()]);
            }

            return $this->redirect(['action' => 'add', 'new']);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurder = $entityManager->find(OdpHuurder::class, $id);

        $form = $this->createForm(OdpHuurderType::class, $odpHuurder);
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
        $this->set('odpHuurder', $odpHuurder);
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurder = $entityManager->find(OdpHuurder::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($odpHuurder);
            $entityManager->flush();

            $this->Session->setFlash('Klant is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('odpHuurder', $odpHuurder);
        $this->set('form', $form->createView());
    }
}
