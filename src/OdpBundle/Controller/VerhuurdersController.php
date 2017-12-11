<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use Doctrine\ORM\QueryBuilder;
use OdpBundle\Entity\Verhuurder;
use OdpBundle\Form\VerhuurderCloseType;
use OdpBundle\Form\VerhuurderFilterType;
use OdpBundle\Form\VerhuurderSelectType;
use OdpBundle\Form\VerhuurderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;

/**
 * @Route("/verhuurders")
 */
class VerhuurdersController extends SymfonyController
{
    public $title = 'Verhuurders';

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'klant.werkgebied',
        'verhuurder.aanmelddatum',
        'verhuurder.afsluitdatum',
        'verhuurder.wpi',
        'verhuurder.ksgw',
    ];

    /**
     * @Route("/")
     */
    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Verhuurder::class);

        $builder = $repository->createQueryBuilder('verhuurder')
            ->innerJoin('verhuurder.klant', 'klant')
            ->leftJoin('verhuurder.afsluiting', 'afsluiting')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
            ->andWhere('klant.disabled = false')
        ;

        $filter = $this->createForm(VerhuurderFilterType::class);
        $filter->handleRequest($this->getRequest());
        if ($filter->isSubmitted() && $filter->isValid()) {
            $filter->getData()->applyTo($builder);
            if ($filter->get('download')->isClicked()) {
                return $this->download($builder);
            }
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'klant.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        return [
            'filter' => $filter->createView(),
            'pagination' => $pagination,
        ];
    }

    private function download(QueryBuilder $builder)
    {
        ini_set('memory_limit', '512M');

        $verhuurders = $builder->getQuery()->getResult();

        $this->autoRender = false;
        $filename = sprintf('onder-de-pannen-verhuurders-%s.xlsx', (new \DateTime())->format('d-m-Y'));

        /** @var $export ExportInterface */
        $export = $this->container->get('odp.export.verhuurders');

        return $export->create($verhuurders)->getResponse($filename);
    }

    /**
     * @Route("/{id}/view")
     */
    public function view($id)
    {
        $verhuurder = $this->getEntityManager()->find(Verhuurder::class, $id);

        return ['verhuurder' => $verhuurder];
    }

    /**
     * @Route("/add")
     */
    public function add($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($this->getRequest()->query->has('klantId')) {
            $klant = new Klant();
            if ('new' !== $this->getRequest()->query->get('klantId')) {
                $klant = $entityManager->find(Klant::class, $this->getRequest()->query->get('klantId'));
            }

            $verhuurder = new Verhuurder();
            $verhuurder->setKlant($klant);

            $creationForm = $this->createForm(VerhuurderType::class, $verhuurder);
            $creationForm->handleRequest($this->getRequest());

            if ($creationForm->isValid()) {
                try {
                    $entityManager->persist($verhuurder->getKlant());
                    $entityManager->persist($verhuurder);
                    $entityManager->flush();

                    $this->addFlash('success', 'Verhuurder is opgeslagen.');

                    return $this->redirectToRoute('odp_verhuurders_view', ['id' => $verhuurder->getId()]);
                } catch (\Exception $e) {
                    $this->addFlash('success', 'Er is een fout opgetreden.');

                    return $this->redirectToRoute('odp_verhuurders_index');
                }
            }

            return ['creationForm' => $creationForm->createView()];
        }

        $filterForm = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->add('submit', SubmitType::class, ['label' => 'Verder']);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(VerhuurderSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            return ['selectionForm' => $selectionForm->createView()];
        }

        if ($selectionForm->isSubmitted() && $selectionForm->isValid()) {
            $verhuurder = $selectionForm->getData();
            if ($verhuurder->getKlant() instanceof Klant) {
                $id = $verhuurder->getKlant()->getId();
            } else {
                $id = 'new';
            }

            return $this->redirectToRoute('odp_verhuurders_add', ['klantId' => $id]);
        }

        return ['filterForm' => $filterForm->createView()];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $verhuurder = $entityManager->find(Verhuurder::class, $id);

        $form = $this->createForm(VerhuurderType::class, $verhuurder);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Verhuurder is opgeslagen.');

                return $this->redirectToRoute('odp_verhuurders_view', ['id' => $verhuurder->getId()]);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        return [
            'verhuurder' => $verhuurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/close")
     */
    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $verhuurder = $entityManager->find(Verhuurder::class, $id);

        $form = $this->createForm(VerhuurderCloseType::class, $verhuurder);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Verhuurder is afgesloten.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_verhuurders_view', ['id' => $verhuurder->getId()]);
        }

        return [
            'verhuurder' => $verhuurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopen($id)
    {
        $entityManager = $this->getEntityManager();
        $verhuurder = $entityManager->find(Verhuurder::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $verhuurder->reopen();
                    $entityManager->flush();

                    $this->addFlash('success', 'Huurder is heropend.');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }
            }

            return $this->redirectToRoute('odp_verhuurders_view', ['id' => $verhuurder->getId()]);
        }

        return [
            'verhuurder' => $verhuurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $verhuurder = $entityManager->find(Verhuurder::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($verhuurder);
                $entityManager->flush();

                $this->addFlash('success', 'Verhuurder is verwijderd.');

                return $this->redirectToRoute('odp_verhuurders_index');
            } else {
                return $this->redirectToRoute('odp_verhuurders_view', ['id' => $verhuurder->getId()]);
            }
        }

        return [
            'verhuurder' => $verhuurder,
            'form' => $form->createView(),
        ];
    }
}
