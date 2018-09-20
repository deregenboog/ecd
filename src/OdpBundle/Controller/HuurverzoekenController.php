<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use Doctrine\ORM\QueryBuilder;
use OdpBundle\Entity\Huurder;
use OdpBundle\Entity\Huurverzoek;
use OdpBundle\Form\HuurverzoekCloseType;
use OdpBundle\Form\HuurverzoekFilterType;
use OdpBundle\Form\HuurverzoekType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;

/**
 * @Route("/huurverzoeken")
 * @Template
 */
class HuurverzoekenController extends SymfonyController
{
    public $title = 'Huurverzoeken';

    private $sortFieldWhitelist = [
        'huurverzoek.id',
        'klant.achternaam',
        'werkgebied.naam',
        'huurverzoek.startdatum',
        'huurverzoek.afsluitdatum',
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
            ->innerJoin('huurverzoek.huurder', 'huurder')
            ->innerJoin('huurder.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('huurverzoek.afsluiting', 'afsluiting')
            ->andWhere('huurovereenkomst.id IS NULL')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;

        $filter = $this->createForm(HuurverzoekFilterType::class);
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

        $huurverzoeken = $builder->getQuery()->getResult();

        $this->autoRender = false;
        $filename = sprintf('onder-de-pannen-huurverzoeken-%s.xlsx', (new \DateTime())->format('d-m-Y'));

        /** @var $export ExportInterface */
        $export = $this->container->get('odp.export.huurverzoeken');

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
     * @Route("/huurders/{id}/huurverzoeken/add")
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
        $huurverzoek = $entityManager->find(Huurverzoek::class, $id);

        $form = $this->createForm(HuurverzoekCloseType::class, $huurverzoek);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Huurverzoek is afgesloten.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('odp_huurverzoeken_view', ['id' => $huurverzoek->getId()]);
        }

        return [
            'huurverzoek' => $huurverzoek,
            'form' => $form->createView(),
        ];
    }
}
