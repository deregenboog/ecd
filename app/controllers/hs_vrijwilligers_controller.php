<?php

use App\Entity\Vrijwilliger;
use HsBundle\Entity\HsVrijwilliger;
use App\Form\HsVrijwilligerType;
use Symfony\Component\Form\FormError;
use Doctrine\DBAL\DBALException;

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

    private $sortFieldWhitelist = [
        'vrijwilliger.achternaam',
    ];

    public function index()
    {
//         $form = $this->createForm(HsVrijwilligerFilterType::class);
//         $form->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsVrijwilliger::class);

        $builder = $repository->createQueryBuilder('hsVrijwilliger')
            ->innerJoin('hsVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->andWhere('vrijwilliger.disabled = false')
        ;

//         if ($form->isValid()) {
//             $filter = $form->getData()->applyTo($builder);
//         }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'vrijwilliger.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

//         $this->set('form', $form);
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
            $creationForm->handleRequest($this->request);

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

        $filterForm = $this->createForm(VrijwilligerFilterType::class)->remove('stadsdeel');
        $filterForm->handleRequest($this->request);

        $selectionForm = $this->createForm(HsVrijwilligerSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->request);

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
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $entityManager->flush();
                $this->Session->setFlash('Vrijwilliger is opgeslagen.');

                return $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
    }
}
