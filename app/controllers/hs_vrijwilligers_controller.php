<?php

use HsBundle\Entity\HsVrijwilliger;
use HsBundle\Form\HsVrijwilligerType;
use Symfony\Component\Form\FormError;
use HsBundle\Form\HsVrijwilligerFilterType;
use AppBundle\Form\VrijwilligerFilterType;
use HsBundle\Form\HsVrijwilligerSelectType;
use AppBundle\Entity\Vrijwilliger;
use HsBundle\Entity\HsMemo;
use HsBundle\Form\HsMemoType;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Medewerker;

class HsVrijwilligersController extends AppController
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
        'dragend',
        'rijbewijs',
        'vrijwilliger' => ['naam', 'stadsdeel'],
    ];

    private $sortFieldWhitelist = [
        'hsVrijwilliger.id',
        'vrijwilliger.achternaam',
        'vrijwilliger.werkgebied',
    ];

    public function index()
    {
        $filter = $this->createForm(HsVrijwilligerFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsVrijwilliger::class);

        $builder = $repository->createQueryBuilder('hsVrijwilliger')
            ->innerJoin('hsVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->andWhere('vrijwilliger.disabled = false')
        ;

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'vrijwilliger.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $hsVrijwilliger = $entityManager->find(HsVrijwilliger::class, $id);
        $this->set('hsVrijwilliger', $hsVrijwilliger);
    }

    public function add($vrijwilligerId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($vrijwilligerId) {
            $vrijwilliger = new Vrijwilliger();
            if ($vrijwilligerId !== 'new') {
                $vrijwilliger = $entityManager->find(Vrijwilliger::class, $vrijwilligerId);
            }

            $hsVrijwilliger = new HsVrijwilliger();
            $hsVrijwilliger->setVrijwilliger($vrijwilliger);

            $creationForm = $this->createForm(HsVrijwilligerType::class, $hsVrijwilliger);
            $creationForm->handleRequest($this->getRequest());

            if ($creationForm->isValid()) {
                try {
                    $entityManager->persist($hsVrijwilliger);
                    $entityManager->flush();

                    $this->Session->setFlash('Vrijwilliger is opgeslagen.');

                    return $this->redirect(array('action' => 'view', $hsVrijwilliger->getId()));
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

        $filterForm = $this->createForm(VrijwilligerFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(HsVrijwilligerSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if ($filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isValid()) {
            $hsVrijwilliger = $selectionForm->getData();
            if ($hsVrijwilliger->getVrijwilliger() instanceof Vrijwilliger) {
                return $this->redirect(['action' => 'add', $hsVrijwilliger->getVrijwilliger()->getId()]);
            }

            return $this->redirect(['action' => 'add', 'new']);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $hsVrijwilliger = $entityManager->find(HsVrijwilliger::class, $id);

        $form = $this->createForm(HsVrijwilligerType::class, $hsVrijwilliger);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            try {
                $entityManager->flush();
                $this->Session->setFlash('Vrijwilliger is opgeslagen.');

                return $this->redirect(['action' => 'view', $hsVrijwilliger->getId()]);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('hsVrijwilliger', $hsVrijwilliger);
        $this->set('form', $form->createView());
    }

    public function delete($id)
    {
        $form = $this->createForm(HsKlantType::class, new HsBundle\Entity\HsKlant());
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            return $this->redirect(['action' => 'add2']);
        }

        $this->set('form', $form->createView());
    }

    public function memos_index($hsVrijwilligerId)
    {
        $entityManager = $this->getEntityManager();
        $hsVrijwilliger = $entityManager->find(HsVrijwilliger::class, $hsVrijwilligerId);

        $builder = $entityManager->getRepository(HsMemo::class)->createQueryBuilder('hsMemo')
            ->innerJoin(HsVrijwilliger::class, 'hsVrijwilliger', 'WITH', 'hsVrijwilliger = :hsVrijwilliger')
            ->innerJoin('hsVrijwilliger.hsMemos', 'hsMemos', 'WITH', 'hsMemos = hsMemo')
            ->setParameter('hsVrijwilliger', $hsVrijwilliger)
        ;

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'hsMemo.datum',
            'defaultSortDirection' => 'desc',
            'sortFieldWhitelist' => ['hsMemo.datum'],
        ]);

        $this->set('hsVrijwilliger', $hsVrijwilliger);
        $this->set('pagination', $pagination);
    }

    public function memos_add($hsVrijwilligerId)
    {
        $entityManager = $this->getEntityManager();
        $hsVrijwilliger = $entityManager->find(HsVrijwilliger::class, $hsVrijwilligerId);

        $medewerkerId = $this->Session->read('Auth.Medewerker.id');
        $medewerker = $this->getEntityManager()->find(Medewerker::class, $medewerkerId);

        $hsMemo = new HsMemo($medewerker);
        if (count($hsVrijwilliger->getHsMemos()) === 0) {
            $hsMemo->setIntake(true);
        }

        $form = $this->createForm(HsMemoType::class, $hsMemo);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            try {
                $hsVrijwilliger->addHsMemo($hsMemo);
                $entityManager->flush();

                $this->Session->setFlash('Memo is opgeslagen.');
            } catch (\Exception $e) {
                $this->Session->setFlash('Er is een fout opgetreden.');
            } finally {
                return $this->redirect(array('action' => 'view', $hsVrijwilliger->getId()));
            }
        }

        $this->set('hsVrijwilliger', $hsVrijwilliger);
        $this->set('form', $form->createView());
    }
}
