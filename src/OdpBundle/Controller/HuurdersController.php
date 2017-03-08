<?php

namespace OdpBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\ConfirmationType;
use OdpBundle\Entity\Huurder;
use OdpBundle\Form\HuurderType;
use OdpBundle\Form\HuurderFilterType;
use OdpBundle\Form\HuurderSelectType;
use AppBundle\Controller\SymfonyController;
use OdpBundle\Form\HuurderCloseType;

class HuurdersController extends SymfonyController
{
    private $enabledFilters = [
        'klant' => ['id', 'naam', 'stadsdeel'],
        'aanmelddatum',
        'afsluitdatum',
    ];

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'klant.werkgebied',
        'huurder.aanmelddatum',
        'huurder.afsluitdatum',
    ];

    /**
     * @Route("/odp/huurders")
     */
    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Huurder::class);

        $builder = $repository->createQueryBuilder('huurder')
            ->innerJoin('huurder.klant', 'klant')
            ->andWhere('klant.disabled = false')
        ;

        $filter = $this->createForm(HuurderFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());
        if ($filter->isSubmitted() && $filter->isValid()) {
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

    /**
     * @Route("/odp/huurders/{id}/view")
     */
    public function view($id)
    {
        $huurder = $this->getEntityManager()->find(Huurder::class, $id);

        return [
            'huurder' => $huurder,
        ];
    }

    /**
     * @Route("/odp/huurders/add")
     */
    public function add($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($this->getRequest()->query->has('klantId')) {
            $klant = new Klant();
            if ($this->getRequest()->query->get('klantId') !== 'new') {
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
                    $this->addFlash('danger', 'Er is een fout opgetreden.');

                    return $this->redirectToRoute('odp_huurders_index');
                }
            }

            $this->set('creationForm', $creationForm->createView());

            return;
        }

        $filterForm = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'geboortedatum'],
        ]);
        $filterForm->add('submit', SubmitType::class, ['label' => 'Verder']);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(HuurderSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isValid()) {
            $huurder = $selectionForm->getData();
            if ($huurder->getKlant() instanceof Klant) {
                $id = $huurder->getKlant()->getId();
            } else {
                $id = 'new';
            }

            return $this->redirectToRoute('odp_huurders_add', ['klantId' => $id]);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    /**
     * @Route("/odp/huurders/{id}/edit")
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

        $this->set('form', $form->createView());
        $this->set('huurder', $huurder);
    }

    /**
     * @Route("/odp/huurders/{id}/close")
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
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_huurders_view', ['id' => $huurder->getId()]);
        }

        return [
            'huurder' => $huurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/odp/huurders/{id}/delete")
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

        $this->set('huurder', $huurder);
        $this->set('form', $form->createView());
    }
}
