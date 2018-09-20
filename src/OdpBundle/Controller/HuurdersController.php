<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use Doctrine\ORM\QueryBuilder;
use OdpBundle\Entity\Huurder;
use OdpBundle\Form\HuurderCloseType;
use OdpBundle\Form\HuurderFilterType;
use OdpBundle\Form\HuurderSelectType;
use OdpBundle\Form\HuurderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;

/**
 * @Route("/huurders")
 * @Template
 */
class HuurdersController extends SymfonyController
{
    public $title = 'Huurders';

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'werkgebied.naam',
        'huurder.automatischeIncasso',
        'huurder.aanmelddatum',
        'huurder.afsluitdatum',
        'huurder.wpi',
    ];

    /**
     * @Route("/")
     */
    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Huurder::class);

        $builder = $repository->createQueryBuilder('huurder')
            ->innerJoin('huurder.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('huurder.afsluiting', 'afsluiting')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;

        $filter = $this->createForm(HuurderFilterType::class);
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

        $huurders = $builder->getQuery()->getResult();

        $this->autoRender = false;
        $filename = sprintf('onder-de-pannen-huurders-%s.xlsx', (new \DateTime())->format('d-m-Y'));

        /** @var $export ExportInterface */
        $export = $this->container->get('odp.export.huurders');

        return $export->create($huurders)->getResponse($filename);
    }

    /**
     * @Route("/{id}/view")
     */
    public function view($id)
    {
        $huurder = $this->getEntityManager()->find(Huurder::class, $id);

        return [
            'huurder' => $huurder,
        ];
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

            $huurder = new Huurder();
            $huurder->setKlant($klant);

            $creationForm = $this->createForm(HuurderType::class, $huurder);
            $creationForm->handleRequest($this->getRequest());

            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                try {
                    $entityManager->persist($huurder->getKlant());
                    $entityManager->persist($huurder);
                    $entityManager->flush();

                    $this->addFlash('success', 'Huurder is opgeslagen.');

                    return $this->redirectToRoute('odp_huurders_view', ['id' => $huurder->getId()]);
                } catch (\Exception $e) {
                    $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);

                    return $this->redirectToRoute('odp_huurders_index');
                }
            }

            return [
                'creationForm' => $creationForm->createView(),
            ];
        }

        $filterForm = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->add('submit', SubmitType::class, ['label' => 'Verder']);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(HuurderSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            return ['selectionForm' => $selectionForm->createView()];
        }

        if ($selectionForm->isSubmitted() && $selectionForm->isValid()) {
            $huurder = $selectionForm->getData();
            if ($huurder->getKlant() instanceof Klant) {
                $id = $huurder->getKlant()->getId();
            } else {
                $id = 'new';
            }

            return $this->redirectToRoute('odp_huurders_add', ['klantId' => $id]);
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $huurder = $entityManager->find(Huurder::class, $id);

        $form = $this->createForm(HuurderType::class, $huurder);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Huurder is opgeslagen.');

                return $this->redirectToRoute('odp_huurders_view', ['id' => $huurder->getId()]);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        return [
            'huurder' => $huurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/close")
     */
    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $huurder = $entityManager->find(Huurder::class, $id);

        $form = $this->createForm(HuurderCloseType::class, $huurder);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Huurder is afgesloten.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('odp_huurders_view', ['id' => $huurder->getId()]);
        }

        return [
            'huurder' => $huurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopen($id)
    {
        $entityManager = $this->getEntityManager();
        $huurder = $entityManager->find(Huurder::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $huurder->reopen();
                    $entityManager->flush();

                    $this->addFlash('success', 'Huurder is heropend.');
                } catch (\Exception $e) {
                    $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
                }
            }

            return $this->redirectToRoute('odp_huurders_view', ['id' => $huurder->getId()]);
        }

        return [
            'huurder' => $huurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $huurder = $entityManager->find(Huurder::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($huurder);
                $entityManager->flush();

                $this->addFlash('success', 'Huurder is verwijderd.');

                return $this->redirectToRoute('odp_huurders_index');
            } else {
                return $this->redirectToRoute('odp_huurders_view', ['id' => $huurder->getId()]);
            }
        }

        return [
            'huurder' => $huurder,
            'form' => $form->createView(),
        ];
    }
}
