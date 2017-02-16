<?php

use AppBundle\Entity\Klant;
use OekBundle\Entity\OekKlant;
use OekBundle\Form\OekKlantType;
use AppBundle\Form\KlantFilterType;
use OekBundle\Form\OekKlantSelectType;
use Doctrine\DBAL\Driver\PDOException;
use OekBundle\Form\OekKlantFilterType;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\OekAanmelding;
use OekBundle\Form\OekAanmeldingType;
use OekBundle\Form\OekAfsluitingType;
use OekBundle\Entity\OekAfsluiting;

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
        'klant' => ['id', 'naam', 'stadsdeel'],
        'training',
        'aanmelddatum',
        'afsluitdatum',
    ];

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'klant.werkgebied',
        'oekTraining.naam',
        'oekAanmelding.datum',
        'oekAfsluiting.datum',
    ];

    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(OekKlant::class);
        $builder = $repository->createQueryBuilder('oekKlant')
            ->select('oekKlant, oekAanmelding, oekAfsluiting, oekDossierStatus, oekGroep, oekTraining')
            ->innerJoin('oekKlant.klant', 'klant')
            ->leftJoin('oekKlant.oekAanmelding', 'oekAanmelding')
            ->leftJoin('oekKlant.oekAfsluiting', 'oekAfsluiting')
            ->leftJoin('oekKlant.oekDossierStatus', 'oekDossierStatus')
            ->leftJoin('oekKlant.oekGroepen', 'oekGroep')
            ->leftJoin('oekKlant.oekTrainingen', 'oekTraining')
            ->andWhere('klant.disabled = false')
        ;

        $filter = $this->createFilter();
        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
            if ($filter->get('download')->isClicked()) {
                return $this->download($builder);
            }
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

    public function download(QueryBuilder $builder)
    {
        $oekKlanten = $builder->getQuery()->getResult();

        $filename = sprintf('op-eigen-kracht-deelnemers-%s.csv', (new \DateTime())->format('d-m-Y'));
        $this->header('Content-type: text/csv');
        $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));

        $this->set('oekKlanten', $oekKlanten);
        $this->render('download', false);
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

                return $this->redirect(['action' => 'view', $oekKlant->getId()]);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('oekKlant', $oekKlant);
    }

    public function open($id)
    {
        $entityManager = $this->getEntityManager();
        $oekKlant = $entityManager->find(OekKlant::class, $id);

        $oekAanmelding = new OekAanmelding();
        $form = $this->createForm(OekAanmeldingType::class, $oekAanmelding);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $oekKlant->addOekAanmelding($oekAanmelding);
                $entityManager->flush();

                $this->Session->setFlash('Het dossier is heropend.');

                return $this->redirect(['action' => 'view', $oekKlant->getId()]);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('oekKlant', $oekKlant);
    }

    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $oekKlant = $entityManager->find(OekKlant::class, $id);

        $oekAfsluiting = new OekAfsluiting();
        $form = $this->createForm(OekAfsluitingType::class, $oekAfsluiting);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $oekKlant->addOekAfsluiting($oekAfsluiting);
                $entityManager->flush();

                $this->Session->setFlash('Het dossier is afgesloten.');

                return $this->redirect(['action' => 'view', $oekKlant->getId()]);
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

    /**
     * @return FormInterface
     */
    private function createFilter()
    {
        $filter = $this->createForm(OekKlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        return $filter;
    }
}
