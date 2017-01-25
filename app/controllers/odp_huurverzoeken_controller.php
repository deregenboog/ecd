<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpHuurverzoek;
use OdpBundle\Form\OdpHuurverzoekType;
use AppBundle\Form\KlantFilterType;
use OdpBundle\Form\OdpHuurverzoekSelectType;
use Doctrine\DBAL\Driver\PDOException;
use OdpBundle\Form\OdpHuurverzoekFilterType;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use OdpBundle\Entity\HsMemo;
use OdpBundle\Form\HsMemoType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OdpHuurverzoekenController extends AppController
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
    ];

    private $sortFieldWhitelist = [
        'odpHuurverzoek.id',
        'klant.achternaam',
        'klant.werkgebied',
    ];

    public function index()
    {
        $filter = $this->createForm(OdpHuurverzoekFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OdpHuurverzoek::class);

        $builder = $repository->createQueryBuilder('odpHuurverzoek')
            ->innerJoin('odpHuurverzoek.odpHuurder', 'odpHuurder')
            ->innerJoin('odpHuurder.klant', 'klant');

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
//             'defaultSortFieldName' => 'klant.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurverzoek = $entityManager->find(OdpHuurverzoek::class, $id);
        $this->set('odpHuurverzoek', $odpHuurverzoek);
        $this->set('odpHuurder', $odpHuurverzoek->getOdpHuurder());
    }

    public function add($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($klantId) {
            $klant = new Klant();
            if ($klantId !== 'new') {
                $klant = $entityManager->find(Klant::class, $klantId);
            }

            $odpHuurverzoek = new OdpHuurverzoek();
            $odpHuurverzoek->setKlant($klant);

            $creationForm = $this->createForm(OdpHuurverzoekType::class, $odpHuurverzoek);
            $creationForm->add('memo', TextareaType::class, [
                'label' => 'Intake-memo',
                'mapped' => false,
                'attr' => ['rows' => 10, 'cols' => 80],
            ]);
            $creationForm->handleRequest($this->request);

            if ($creationForm->isValid()) {
                try {
                    $hsMemo = new HsMemo($odpHuurverzoek->getKlant()->getMedewerker());
                    $hsMemo->setMemo($creationForm->get('memo')->getData());
                    $odpHuurverzoek->addHsMemo($hsMemo);

                    $entityManager->persist($odpHuurverzoek);
                    $entityManager->flush();

                    $this->Session->setFlash('Klant is opgeslagen.');

                    return $this->redirect(array('action' => 'view', $odpHuurverzoek->getId()));
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

        $selectionForm = $this->createForm(OdpHuurverzoekSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->request);

        if ($filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isValid()) {
            $odpHuurverzoek = $selectionForm->getData();
            if ($odpHuurverzoek->getKlant() instanceof Klant) {
                return $this->redirect(['action' => 'add', $odpHuurverzoek->getKlant()->getId()]);
            }

            return $this->redirect(['action' => 'add', 'new']);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurverzoek = $entityManager->find(OdpHuurverzoek::class, $id);

        $form = $this->createForm(OdpHuurverzoekType::class, $odpHuurverzoek);
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
        $this->set('odpHuurverzoek', $odpHuurverzoek);
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurverzoek = $entityManager->find(OdpHuurverzoek::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($odpHuurverzoek);
            $entityManager->flush();

            $this->Session->setFlash('Klant is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('odpHuurverzoek', $odpHuurverzoek);
        $this->set('form', $form->createView());
    }
}
