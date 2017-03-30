<?php

namespace OdpBundle\Controller;

use AppBundle\Entity\Klant;
use OdpBundle\Entity\Huurder;
use OdpBundle\Entity\Huurovereenkomst;
use OdpBundle\Form\HuurovereenkomstFilterType;
use OdpBundle\Form\HuurovereenkomstType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use OdpBundle\Entity\Huurverzoek;
use OdpBundle\Entity\Huuraanbod;
use OdpBundle\Exception\OdpException;
use Doctrine\ORM\EntityManager;
use AppBundle\Controller\SymfonyController;
use OdpBundle\Form\HuurovereenkomstCloseType;

class HuurovereenkomstenController extends SymfonyController
{
    private $enabledFilters = [
        'id',
        'huurderKlant' => ['naam'],
        'verhuurderKlant' => ['naam'],
        'medewerker',
        'startdatum',
        'opzegdatum',
        'afsluitdatum',
    ];

    private $sortFieldWhitelist = [
        'huurovereenkomst.id',
        'huurderKlant.achternaam',
        'verhuurderKlant.achternaam',
        'medewerker.voornaam',
        'huurovereenkomst.startdatum',
        'huurovereenkomst.opzegdatum',
        'huurovereenkomst.afsluitdatum',
    ];

    /**
     * @Route("/odp/huurovereenkomsten")
     */
    public function index()
    {
        $filter = $this->createForm(HuurovereenkomstFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Huurovereenkomst::class);

        $builder = $repository->createQueryBuilder('huurovereenkomst')
            ->innerJoin('huurovereenkomst.huurverzoek', 'huurverzoek')
            ->innerJoin('huurovereenkomst.huuraanbod', 'huuraanbod')
            ->innerJoin('huurovereenkomst.medewerker', 'medewerker')
            ->innerJoin('huurverzoek.huurder', 'huurder')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->innerJoin('huurder.klant', 'huurderKlant')
            ->innerJoin('verhuurder.klant', 'verhuurderKlant')
            ->andWhere('huurderKlant.disabled = false')
            ->andWhere('verhuurderKlant.disabled = false')
        ;

        if ($filter->isSubmitted() && $filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'huurovereenkomst.id',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        return [
            'filter' => $filter->createView(),
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/odp/huurovereenkomsten/{id}/view")
     */
    public function view($id)
    {
        $huurovereenkomst = $this->getEntityManager()->find(Huurovereenkomst::class, $id);

        return ['huurovereenkomst' => $huurovereenkomst];
    }

    /**
     * @Route("/odp/huurovereenkomsten/add")
     */
    public function add()
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->findEntity($entityManager);

        $huurovereenkomst = new Huurovereenkomst();
        $huurovereenkomst->setMedewerker($this->getMedewerker());
        if ($entity instanceof Huurverzoek) {
            $huurovereenkomst->setHuurverzoek($entity);
        } elseif ($entity instanceof Huuraanbod) {
            $huurovereenkomst->setHuuraanbod($entity);
        }

        $form = $this->createForm(HuurovereenkomstType::class, $huurovereenkomst);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $routeBase = $this->resolveRouteBase($entity);
            try {
                $entityManager->persist($huurovereenkomst);
                $entityManager->flush();

                $this->addFlash('success', 'Huurovereenkomst is opgeslagen.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');

                return $this->redirectToRoute($routeBase.'_index');
            }

            return $this->redirectToRoute('odp_huurovereenkomsten_view', ['id' => $huurovereenkomst->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/odp/huurovereenkomsten/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $huurovereenkomst = $entityManager->find(Huurovereenkomst::class, $id);

        $form = $this->createForm(HuurovereenkomstType::class, $huurovereenkomst);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_huurovereenkomsten_view', ['id' => $huurovereenkomst->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/odp/huurovereenkomsten/{id}/close")
     */
    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $huurovereenkomst = $entityManager->find(Huurovereenkomst::class, $id);

        $form = $this->createForm(HuurovereenkomstCloseType::class, $huurovereenkomst);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_huurovereenkomsten_view', ['id' => $huurovereenkomst->getId()]);
        }

        return ['form' => $form->createView()];
    }

    private function findEntity(EntityManager $entityManager)
    {
        switch (true) {
            case $this->getRequest()->query->has('huurverzoek'):
                $class = Huurverzoek::class;
                $id = $this->getRequest()->query->get('huurverzoek');
                break;
            case $this->getRequest()->query->has('huuraanbod'):
                $class = Huuraanbod::class;
                $id = $this->getRequest()->query->get('huuraanbod');
                break;
            default:
                throw new OdpException('Kan geen koppeling voor deze entiteit maken');
        }

        return $entityManager->find($class, $id);
    }

    private function resolveRouteBase($entity)
    {
        switch (true) {
            case $entity instanceof Huurverzoek:
                $routeBase = 'odp_huurverzoeken';
                break;
            case $entity instanceof Huuraanbod:
                $routeBase = 'odp_huuraanbiedingen';
                break;
            default:
                throw new OdpException('Kan geen verslag aan deze entiteit toevoegen');
        }

        return $routeBase;
    }
}
