<?php

use AppBundle\Entity\Klant;
use OekBundle\Entity\OekKlant;
use OekBundle\Form\Model\OekKlantModel;
use OekBundle\Form\OekKlantGroepType;
use OekBundle\Form\OekKlantTrainingType;
use OekBundle\Form\OekKlantType;
use AppBundle\Form\KlantFilterType;
use OekBundle\Form\OekKlantSelectType;
use Doctrine\DBAL\Driver\PDOException;
use OekBundle\Form\OekKlantFilterType;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OekKlantenController extends AppController
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
        'aanmelding',
        'afsluiting'
    ];

    private $sortFieldWhitelist = [
        'oekKlant.id',
        'klant.achternaam',
        'klant.werkgebied',
        'oekKlant.aanmelding',
        'oekKlant.afsluiting',
    ];

    public function index()
    {
        $filter = $this->createForm(OekKlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekKlant::class);

        $builder = $repository->createQueryBuilder('oekKlant')
            ->innerJoin('oekKlant.klant', 'klant')
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

    public function wachtlijst()
    {
        $filter = $this->createForm(OekKlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters + ['groepen'],
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekKlant::class);

        $builder = $repository->createQueryBuilder('oekKlant')
            ->innerJoin('oekKlant.klant', 'klant')
            ->innerJoin('oekKlant.oekGroepen', 'groepen')
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
        $oekKlant = $entityManager->find(OekKlant::class, $id);
        $this->set('oekKlant', $oekKlant);
    }

    public function add($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($klantId) {
            if ($klantId === 'new') {
                $klant = new Klant();
                $medewerkerId = $this->Session->read('Auth.Medewerker.id');
                $medewerker = $this->getEntityManager()->find(Medewerker::class, $medewerkerId);
                $klant->setMedewerker($medewerker);
            } else {
                $klant = $entityManager->find(Klant::class, $klantId);
            }

            $oekKlant = new OekKlant();
            $oekKlant->setKlant($klant);

            $creationForm = $this->createForm(OekKlantType::class, $oekKlant);
            $creationForm->handleRequest($this->request);

            if ($creationForm->isValid()) {
                try {
                    $entityManager->persist($oekKlant);
                    $entityManager->flush();

                    $this->Session->setFlash('Klant is opgeslagen.');

                    return $this->redirect(array('action' => 'view', $oekKlant->getId()));
                } catch (\Exception $e) {
                    if ($e->getPrevious() instanceof PDOException && $e->getPrevious()->getCode() == 23000) {
                        $this->Session->setFlash('Deze klant heeft al een Op-eigen-kracht-dossier.');
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

        $selectionForm = $this->createForm(OekKlantSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->request);

        if ($filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isValid()) {
            $oekKlant = $selectionForm->getData();
            if ($oekKlant->getKlant() instanceof Klant) {
                return $this->redirect(['action' => 'add', $oekKlant->getKlant()->getId()]);
            }

            return $this->redirect(['action' => 'add', 'new']);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $oekKlant = $entityManager->find(OekKlant::class, $id);

        $form = $this->createForm(OekKlantType::class, $oekKlant);
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
        $this->set('oekKlant', $oekKlant);
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $oekKlant = $entityManager->find(OekKlant::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($oekKlant);
            $entityManager->flush();

            $this->Session->setFlash('Klant is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('oekKlant', $oekKlant);
        $this->set('form', $form->createView());
    }

    public function voeg_toe_aan_groep($oekKlantId)
    {
        return $this->voeg_toe_aan(OekKlantGroepType::class, $oekKlantId);
    }

    public function voeg_toe_aan_training($oekKlantId)
    {
        return $this->voeg_toe_aan(OekKlantTrainingType::class, $oekKlantId);
    }

    private function voeg_toe_aan($type, $oekKlantId)
    {
        /** @var OekKlant $oekKlant */
        $entityManager = $this->getEntityManager();
        $oekKlant = $entityManager->find(OekKlant::class, $oekKlantId);
        $oekKlantModel = new OekKlantModel($oekKlant);

        $form = $this->createForm($type, $oekKlantModel);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $entityManager->flush();

                $this->Session->setFlash('Klant is toegevoegd.');

                return $this->redirect(array('action' => 'view', $oekKlant->getId()));
                //return $this->redirect(array('action' => 'index'));
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('oekKlant', $oekKlant);
    }
}
