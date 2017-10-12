<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use OdpBundle\Entity\Huuraanbod;
use OdpBundle\Entity\Huurder;
use OdpBundle\Entity\Huurovereenkomst;
use OdpBundle\Entity\Huurverzoek;
use OdpBundle\Exception\OdpException;
use OdpBundle\Form\HuurovereenkomstCloseType;
use OdpBundle\Form\HuurovereenkomstFilterType;
use OdpBundle\Form\HuurovereenkomstType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\ConfirmationType;

/**
 * @Route("/huurovereenkomsten")
 */
class HuurovereenkomstenController extends SymfonyController
{
    public $title = 'Koppelingen';

    private $sortFieldWhitelist = [
        'huurovereenkomst.id',
        'klant.achternaam',
        'verhuurderKlant.achternaam',
        'medewerker.voornaam',
        'huurovereenkomst.startdatum',
        'huurovereenkomst.opzegdatum',
        'huurovereenkomst.einddatum',
        'huurovereenkomst.vorm',
        'huurovereenkomst.afsluitdatum',
        'huurder.automatischeIncasso',
    ];

    /**
     * @Route("/")
     */
    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Huurovereenkomst::class);

        $builder = $repository->createQueryBuilder('huurovereenkomst')
            ->innerJoin('huurovereenkomst.huurverzoek', 'huurverzoek')
            ->innerJoin('huurovereenkomst.huuraanbod', 'huuraanbod')
            ->innerJoin('huurovereenkomst.medewerker', 'medewerker')
            ->innerJoin('huurverzoek.huurder', 'huurder')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->innerJoin('huurder.klant', 'klant')
            ->innerJoin('verhuurder.klant', 'verhuurderKlant')
            ->leftJoin('huurovereenkomst.afsluiting', 'afsluiting')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;

        $filter = $this->createForm(HuurovereenkomstFilterType::class);
        $filter->handleRequest($this->getRequest());
        if ($filter->isSubmitted() && $filter->isValid()) {
            $filter->getData()->applyTo($builder);
            if ($filter->get('download')->isClicked()) {
                return $this->download($builder);
            }
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

    private function download(QueryBuilder $builder)
    {
        ini_set('memory_limit', '512M');

        $overeenkomsten = $builder->getQuery()->getResult();

        $this->autoRender = false;
        $filename = sprintf('onder-de-pannen-koppelingen-%s.xlsx', (new \DateTime())->format('d-m-Y'));

        /** @var $export ExportInterface */
        $export = $this->container->get('odp.export.koppelingen');

        return $export->create($overeenkomsten)->getResponse($filename);
    }

    /**
     * @Route("/{id}/view")
     */
    public function view($id)
    {
        $huurovereenkomst = $this->getEntityManager()->find(Huurovereenkomst::class, $id);

        return ['huurovereenkomst' => $huurovereenkomst];
    }

    /**
     * @Route("/add")
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
     * @Route("/{id}/edit")
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
     * @Route("/{id}/close")
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

    /**
     * @Route("/{id}/reopen")
     */
    public function reopen($id)
    {
        $entityManager = $this->getEntityManager();
        $huurovereenkomst = $entityManager->find(Huurovereenkomst::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $huurovereenkomst->reopen();
                    $entityManager->flush();

                    $this->addFlash('success', 'Koppeling is heropend.');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }
            }

            return $this->redirectToRoute('odp_huurovereenkomsten_view', ['id' => $huurovereenkomst->getId()]);
        }

        return [
            'huurovereenkomst' => $huurovereenkomst,
            'form' => $form->createView(),
        ];
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
