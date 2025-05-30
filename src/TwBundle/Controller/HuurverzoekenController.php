<?php

namespace TwBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\Huurverzoek;
use TwBundle\Entity\Klant;
use TwBundle\Form\HuurverzoekCloseType;
use TwBundle\Form\HuurverzoekFilterType;
use TwBundle\Form\HuurverzoekType;

/**
 * @Route("/huurverzoeken")
 *
 * @Template
 */
class HuurverzoekenController extends SymfonyController
{
    public $title = 'Huurverzoeken';

    private $sortFieldWhitelist = [
        'huurverzoek.id',
        'appKlant.achternaam',
        'werkgebied.naam',
        'huisgenootKlant.achternaam',
        'huurverzoek.startdatum',
        'huurverzoek.afsluitdatum',
        'huurovereenkomst.isReservering',
        'huurverzoek.isActief',
        'medewerker.achternaam',
        'project.naam',
    ];

    /**
     * @Route("/")
     */
    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Huurverzoek::class);

        $builder = $repository->createQueryBuilder('huurverzoek')
            ->leftJoin('huurverzoek.huurovereenkomst', 'huurovereenkomst')
            ->innerJoin('huurverzoek.klant', 'klant')
            ->innerJoin('huurverzoek.medewerker', 'medewerker')
            ->innerJoin('klant.appKlant', 'appKlant')
            ->leftJoin('klant.huisgenoot', 'huisgenoot')
            ->leftJoin('huisgenoot.appKlant', 'huisgenootKlant')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
            ->leftJoin('huurverzoek.afsluiting', 'afsluiting')
            ->andWhere('huurovereenkomst.id IS NULL') // alleen actieve
//            ->andWhere('huurverzoek.afsluitdatum IS NULL OR huurverzoek.afsluitdatum > :now') // alleen actieve
//            ->andWhere('huurovereenkomst.id IS NULL')
//            ->orWhere('huurovereenkomst.isReservering = 1')
//            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;
        $builder->setParameter('now', new \DateTime('now'));

        $filter = $this->getForm(HuurverzoekFilterType::class);
        $filter->handleRequest($this->getRequest());
        $d = $filter->getData();
        $d->applyTo($builder);
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

        $huurverzoeken = $builder->getQuery()->getResult();

        $filename = sprintf('onder-de-pannen-huurverzoeken-%s.xlsx', (new \DateTime())->format('d-m-Y'));

        /* @var $export ExportInterface */
        $export = $this->container->get('tw.export.huurverzoeken');

        return $export->create($huurverzoeken)->getResponse($filename);
    }

    /**
     * @Route("/{id}/view")
     */
    public function view($id)
    {
        $huurverzoek = $this->getEntityManager()->find(Huurverzoek::class, $id);

        return [
            'huurverzoek' => $huurverzoek,
        ];
    }

    /**
     * @Route("/klanten/{id}/huurverzoeken/add")
     */
    public function add($id)
    {
        $entityManager = $this->getEntityManager();
        $huurder = $entityManager->find(Klant::class, $id);

        $huurverzoek = new Huurverzoek();
        $huurverzoek->setKlant($huurder);

        $form = $this->getForm(HuurverzoekType::class, $huurverzoek);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($huurverzoek);
            $entityManager->flush();

            $this->addFlash('success', 'Huurverzoek is toegevoegd.');

            return $this->redirectToRoute('tw_huurverzoeken_view', ['id' => $huurverzoek->getId()]);
        }

        return [
            'huurverzoek' => $huurverzoek,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $huurverzoek = $entityManager->find(Huurverzoek::class, $id);

        $form = $this->getForm(HuurverzoekType::class, $huurverzoek);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Huurverzoek is gewijzigd.');

                return $this->redirectToRoute('tw_huurverzoeken_view', ['id' => $huurverzoek->getId()]);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        return [
            'huurverzoek' => $huurverzoek,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $huurverzoek = $entityManager->find(Huurverzoek::class, $id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($huurverzoek);
                $entityManager->flush();

                $this->addFlash('success', 'Huurverzoek is verwijderd.');

                return $this->redirectToRoute('tw_huurverzoeken_index');
            } else {
                return $this->redirectToRoute('tw_huurverzoeken_view', ['id' => $huurverzoek->getId()]);
            }
        }

        return [
            'huurverzoek' => $huurverzoek,
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
        $huurverzoek = $entityManager->find(Huurverzoek::class, $id);

        $form = $this->getForm(HuurverzoekCloseType::class, $huurverzoek);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Huurverzoek is afgesloten.');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('tw_huurverzoeken_view', ['id' => $huurverzoek->getId()]);
        }

        return [
            'huurverzoek' => $huurverzoek,
            'form' => $form->createView(),
        ];
    }
}
