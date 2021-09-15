<?php

namespace TwBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use Doctrine\ORM\QueryBuilder;
use TwBundle\Entity\Huuraanbod;
use TwBundle\Entity\Verhuurder;
use TwBundle\Form\HuuraanbodCloseType;
use TwBundle\Form\HuuraanbodFilterType;
use TwBundle\Form\HuuraanbodType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;

/**
 * @Route("/huuraanbiedingen")
 * @Template
 */
class HuuraanbiedingenController extends SymfonyController
{
    public $title = 'Huuraanbiedingen';

    private $sortFieldWhitelist = [
        'huuraanbod.id',
        'appKlant.achternaam',
        'werkgebied.naam',
        'huuraanbod.startdatum',
        'huuraanbod.afsluitdatum',
        'huurovereenkomst.isReservering',
        'medewerker.achternaam',
        'project.naam',
    ];

    /**
     * @Route("/")
     */
    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Huuraanbod::class);

        $builder = $repository->createQueryBuilder('huuraanbod')
            ->leftJoin('huuraanbod.huurovereenkomst', 'huurovereenkomst')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->innerJoin('verhuurder.appKlant', 'appKlant')
            ->innerJoin('huuraanbod.medewerker','medewerker')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
            ->leftJoin('huuraanbod.afsluiting', 'afsluiting')
            ->leftJoin('huuraanbod.project', 'project')
            ->andWhere('huurovereenkomst.id IS NULL') //alleen actieve
            ->andWhere('huuraanbod.afsluitdatum IS NULL OR huuraanbod.afsluitdatum > :now') // alleen actieve
//            ->orWhere('huurovereenkomst.isReservering = 1')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;
        $builder->setParameter("now",new \DateTime('now'));

        $filter = $this->getForm(HuuraanbodFilterType::class);

        $filter->handleRequest($this->getRequest());
        $filter->getData()->applyTo($builder);
        if ($filter->get('download')->isClicked()) {
            return $this->download($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'appKlant.achternaam',
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

        $huuraanbiedingen = $builder->getQuery()->getResult();

        $this->autoRender = false;
        $filename = sprintf('onder-de-pannen-huuraanbiedingen-%s.xlsx', (new \DateTime())->format('d-m-Y'));

        /* @var $export ExportInterface */
        $export = $this->container->get('tw.export.huuraanbiedingen');

        return $export->create($huuraanbiedingen)->getResponse($filename);
    }

    /**
     * @Route("/{id}/view")
     */
    public function view($id)
    {
        $huuraanbod = $this->getEntityManager()->find(Huuraanbod::class, $id);

        return ['huuraanbod' => $huuraanbod];
    }

    /**
     * @Route("/verhuurders/{id}/huuraanbiedingen/add")
     */
    public function add($id)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getFilters()->enable('active');
        $verhuurder = $entityManager->find(Verhuurder::class, $id);

        $form = $this->getForm(HuuraanbodType::class, new Huuraanbod());
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $verhuurder->addHuuraanbod($form->getData());
            $entityManager->flush();

            $this->addFlash('success', 'Huuraanbod is toegevoegd.');

            return $this->redirectToRoute('tw_verhuurders_view', ['id' => $verhuurder->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getFilters()->enable('active');
        $huuraanbod = $entityManager->find(Huuraanbod::class, $id);

        $form = $this->getForm(HuuraanbodType::class, $huuraanbod);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Huuraanbod is gewijzigd.');

                return $this->redirectToRoute('tw_huuraanbiedingen_view', ['id' => $huuraanbod->getId()]);
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
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $huuraanbod = $entityManager->find(Huuraanbod::class, $id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($huuraanbod);
                $entityManager->flush();

                $this->addFlash('success', 'Huuraanbod is verwijderd.');

                return $this->redirectToRoute('tw_huuraanbiedingen_index');
            } else {
                return $this->redirectToRoute('tw_huuraanbiedingen_view', ['id' => $huuraanbod->getId()]);
            }
        }

        return [
            'huuraanbod' => $huuraanbod,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/close")
     */
    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getFilters()->enable('active');
        $huuraanbod = $entityManager->find(Huuraanbod::class, $id);

        $form = $this->getForm(HuuraanbodCloseType::class, $huuraanbod);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Huuraanbod is afgesloten.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('tw_huuraanbiedingen_view', ['id' => $huuraanbod->getId()]);
        }

        return [
            'huuraanbod' => $huuraanbod,
            'form' => $form->createView(),
        ];
    }
}
