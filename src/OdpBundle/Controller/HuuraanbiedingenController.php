<?php

namespace OdpBundle\Controller;

use AppBundle\Entity\Klant;
use OdpBundle\Entity\Huuraanbod;
use OdpBundle\Entity\Huurovereenkomst;
use OdpBundle\Form\HuuraanbodType;
use OdpBundle\Form\HuuraanbodFilterType;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use OdpBundle\Entity\Verhuurder;
use AppBundle\Controller\SymfonyController;
use OdpBundle\Form\HuuraanbodCloseType;

class HuuraanbiedingenController extends SymfonyController
{
    private $enabledFilters = [
        'id',
        'klant' => ['naam', 'stadsdeel'],
        'startdatum',
        'afsluitdatum',
    ];

    private $sortFieldWhitelist = [
        'huuraanbod.id',
        'klant.achternaam',
        'klant.werkgebied',
        'huuraanbod.startdatum',
        'huuraanbod.afsluitdatum',
    ];

    /**
     * @Route("/odp/huuraanbiedingen")
     */
    public function index()
    {
        $filter = $this->createForm(HuuraanbodFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Huuraanbod::class);

        $builder = $repository->createQueryBuilder('huuraanbod')
            ->leftJoin('huuraanbod.huurovereenkomst', 'huurovereenkomst')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->innerJoin('verhuurder.klant', 'klant')
            ->where('huurovereenkomst.id IS NULL')
        ;

        if ($filter->isSubmitted() && $filter->isValid()) {
            $filter->getData()->applyTo($builder);
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

    /**
     * @Route("/odp/huuraanbiedingen/{id}/view")
     */
    public function view($id)
    {
        $huuraanbod = $this->getEntityManager()->find(Huuraanbod::class, $id);

        return ['huuraanbod' => $huuraanbod];
    }

    /**
     * @Route("/odp/verhuurders/{id}/huuraanbiedingen/add")
     */
    public function add($id)
    {
        $entityManager = $this->getEntityManager();
        $verhuurder = $entityManager->find(Verhuurder::class, $id);

        $form = $this->createForm(HuuraanbodType::class, new Huuraanbod());
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $verhuurder->addHuuraanbod($form->getData());
            $entityManager->flush();

            $this->addFlash('success', 'Huuraanbod is toegevoegd.');

            return $this->redirectToRoute('odp_verhuurders_view', ['id' => $verhuurder->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/odp/huuraanbiedingen/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $huuraanbod = $entityManager->find(Huuraanbod::class, $id);

        $form = $this->createForm(HuuraanbodType::class, $huuraanbod);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Huuraanbod is gewijzigd.');

                return $this->redirectToRoute('odp_huuraanbiedingen_view', ['id' => $huuraanbod->getId()]);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        return [
            'huuraanbod' => $huuraanbod,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/odp/huuraanbiedingen/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $huuraanbod = $entityManager->find(Huuraanbod::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($huuraanbod);
                $entityManager->flush();

                $this->addFlash('success', 'Huuraanbod is verwijderd.');

                return $this->redirectToRoute('odp_huuraanbiedingen_index');
            } else {
                return $this->redirectToRoute('odp_huuraanbiedingen_view', ['id' => $huuraanbod->getId()]);
            }
        }

        return [
            'huuraanbod' => $huuraanbod,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/odp/huuraanbiedingen/{id}/close")
     */
    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $huuraanbod = $entityManager->find(Huuraanbod::class, $id);

        $form = $this->createForm(HuuraanbodCloseType::class, $huuraanbod);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Huuraanbod is afgesloten.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_huuraanbiedingen_view', ['id' => $huuraanbod->getId()]);
        }

        return [
            'huuraanbod' => $huuraanbod,
            'form' => $form->createView(),
        ];
    }
}
