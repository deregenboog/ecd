<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpHuurder;
use OdpBundle\Entity\OdpHuurverzoek;
use OdpBundle\Form\OdpHuurderType;
use AppBundle\Form\KlantFilterType;
use OdpBundle\Form\OdpHuurderFilterType;
use AppBundle\Form\ConfirmationType;
use OdpBundle\Form\OdpHuurverzoekType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use OdpBundle\Form\OdpHuurderSelectType;
use Symfony\Component\Form\FormError;

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
        'klant' => ['id', 'naam', 'stadsdeel'],
    ];

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'klant.werkgebied',
    ];

    public function index()
    {
        $filter = $this->createForm(OdpHuurderFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OdpHuurder::class);

        $builder = $repository->createQueryBuilder('odpHuurder')
            ->innerJoin('odpHuurder.klant', 'klant')
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
        $odpHuurder = $entityManager->find(OdpHuurder::class, $id);
        $this->set('odpHuurder', $odpHuurder);
    }

    public function add($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($klantId) {
            $klant = new Klant();
            if ('new' !== $klantId) {
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
            $creationForm->handleRequest($this->getRequest());

            if ($creationForm->isValid()) {
                try {
                    $entityManager->persist($odpHuurder->getKlant());
                    $entityManager->persist($odpHuurder);
                    $entityManager->flush();

                    $this->Session->setFlash('Huurder is opgeslagen.');

                    return $this->redirect(['action' => 'view', $odpHuurder->getId()]);
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

        $selectionForm = $this->createForm(OdpHuurderSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

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
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->Session->setFlash('Huurder is opgeslagen.');

                return $this->redirect(['action' => 'view', $odpHuurder->getId()]);
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
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($odpHuurder);
            $entityManager->flush();

            $this->Session->setFlash('Huurder is verwijderd.');

            return $this->redirect(['action' => 'index']);
        }

        $this->set('odpHuurder', $odpHuurder);
        $this->set('form', $form->createView());
    }

    public function nieuw_huurverzoek($odpHuurderId)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurder = $entityManager->find(OdpHuurder::class, $odpHuurderId);

        $odpHuurverzoek = new OdpHuurverzoek();
        $odpHuurverzoek->setOdpHuurder($odpHuurder);

        $form = $this->createForm(OdpHuurverzoekType::class, $odpHuurverzoek);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($odpHuurverzoek);
            $entityManager->flush();

            $this->Session->setFlash('Huurverzoek is toegevoegd.');

            return $this->redirect(['action' => 'view', $odpHuurder->getId()]);
        }

        $this->set('odpHuurverzoek', $odpHuurverzoek);
        $this->set('form', $form->createView());
    }
}
