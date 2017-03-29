<?php

namespace OdpBundle\Controller;

use AppBundle\Entity\Klant;
use OdpBundle\Entity\Huurovereenkomst;
use OdpBundle\Entity\Huurverzoek;
use OdpBundle\Form\HuurverzoekType;
use OdpBundle\Form\HuurverzoekFilterType;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use OdpBundle\Entity\Huurder;
use AppBundle\Controller\SymfonyController;

class HuurverzoekenController extends SymfonyController
{
    private $enabledFilters = [
        'id',
        'klant' => ['naam', 'stadsdeel'],
        'startdatum',
    ];

    private $sortFieldWhitelist = [
        'huurverzoek.id',
        'klant.achternaam',
        'klant.werkgebied',
        'huurverzoek.startdatum',
    ];

    /**
     * @Route("/odp/huurverzoeken")
     */
    public function index()
    {
        $filter = $this->createForm(HuurverzoekFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Huurverzoek::class);

        $builder = $repository->createQueryBuilder('huurverzoek')
            ->leftJoin('huurverzoek.huurovereenkomst', 'huurovereenkomst')
            ->innerJoin('huurverzoek.huurder', 'huurder')
            ->innerJoin('huurder.klant', 'klant')
            ->where('huurovereenkomst.id IS NULL');

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
     * @Route("/odp/huurverzoeken/{id}/view")
     */
    public function view($id)
    {
        $huurverzoek = $this->getEntityManager()->find(Huurverzoek::class, $id);
        $this->set('huurverzoek', $huurverzoek);
    }

    /**
     * @Route("/odp/huurders/{id}/huurverzoeken/add")
     */
    public function add($id)
    {
        $entityManager = $this->getEntityManager();
        $huurder = $entityManager->find(Huurder::class, $id);

        $huurverzoek = new Huurverzoek();
        $huurverzoek->setHuurder($huurder);

        $form = $this->createForm(HuurverzoekType::class, $huurverzoek);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($huurverzoek);
            $entityManager->flush();

            $this->addFlash('success', 'Huurverzoek is toegevoegd.');

            return $this->redirectToRoute('odp_huurverzoeken_view', ['id' => $huurverzoek->getId()]);
        }

        $this->set('huurverzoek', $huurverzoek);
        $this->set('form', $form->createView());
    }

    /**
     * @Route("/odp/huurverzoeken/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $huurverzoek = $entityManager->find(Huurverzoek::class, $id);

        $form = $this->createForm(HuurverzoekType::class, $huurverzoek);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Huurverzoek is gewijzigd.');

                return $this->redirectToRoute('odp_huurverzoeken_view', ['id' => $huurverzoek->getId()]);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('huurverzoek', $huurverzoek);
    }

    /**
     * @Route("/odp/huurverzoeken/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $huurverzoek = $entityManager->find(Huurverzoek::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($huurverzoek);
                $entityManager->flush();

                $this->addFlash('success', 'Huurverzoek is verwijderd.');

                return $this->redirectToRoute('odp_huurverzoeken_index');
            } else {
                return $this->redirectToRoute('odp_huurverzoeken_view', ['id' => $huurverzoek->getId()]);
            }
        }

        $this->set('huurverzoek', $huurverzoek);
        $this->set('form', $form->createView());
    }
}
