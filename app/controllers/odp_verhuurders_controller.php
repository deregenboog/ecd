<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpHuuraanbod;
use OdpBundle\Entity\OdpVerhuurder;
use OdpBundle\Form\OdpHuuraanbodType;
use OdpBundle\Form\OdpVerhuurderType;
use AppBundle\Form\KlantFilterType;
use OdpBundle\Form\OdpVerhuurderSelectType;
use OdpBundle\Form\OdpVerhuurderFilterType;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormError;

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
        'klant' => ['id', 'naam', 'stadsdeel'],
    ];

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'klant.werkgebied',
    ];

    public function index()
    {
        $filter = $this->createForm(OdpVerhuurderFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OdpVerhuurder::class);

        $builder = $repository->createQueryBuilder('odpVerhuurder')
            ->innerJoin('odpVerhuurder.klant', 'klant')
            ->andWhere('klant.disabled = false')
        ;

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
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
            if ('new' !== $klantId) {
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
            $creationForm->handleRequest($this->getRequest());

            if ($creationForm->isValid()) {
                try {
                    $entityManager->persist($odpVerhuurder->getKlant());
                    $entityManager->persist($odpVerhuurder);
                    $entityManager->flush();

                    $this->Session->setFlash('Verhuurder is opgeslagen.');

                    return $this->redirect(['action' => 'view', $odpVerhuurder->getId()]);
                } catch (\Exception $e) {
                    $this->Session->setFlash('Er is een fout opgetreden.');

                    return $this->redirect(['action' => 'index']);
                }
            }

            $this->set('creationForm', $creationForm->createView());

            return;
        }

        $filterForm = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(OdpVerhuurderSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

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
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->Session->setFlash('Klant is opgeslagen.');

                return $this->redirect(['action' => 'index']);
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
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($odpVerhuurder);
            $entityManager->flush();

            $this->Session->setFlash('Klant is verwijderd.');

            return $this->redirect(['action' => 'index']);
        }

        $this->set('odpVerhuurder', $odpVerhuurder);
        $this->set('form', $form->createView());
    }

    public function nieuw_huuraanbod($odpVerhuurderId)
    {
        $entityManager = $this->getEntityManager();
        $odpVerhuurder = $entityManager->find(OdpVerhuurder::class, $odpVerhuurderId);

        $form = $this->createForm(OdpHuuraanbodType::class, new OdpHuuraanbod());
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $odpVerhuurder->addOdpHuuraanbod($form->getData());
            $entityManager->flush();

            $this->Session->setFlash('Huuraanbod is toegevoegd.');

            return $this->redirect(['action' => 'view', $odpVerhuurder->getId()]);
        }

        $this->set('form', $form->createView());
    }
}
