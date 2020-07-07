<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Service\KlantDao;
use AppBundle\Service\KlantDaoInterface;
use Doctrine\ORM\QueryBuilder;

use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\Verhuurder;
use OdpBundle\Form\VerhuurderCloseType;
use OdpBundle\Form\VerhuurderFilterType;
use OdpBundle\Form\VerhuurderSelectType;
use OdpBundle\Form\VerhuurderType;
use OdpBundle\Service\VerhuurderDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;


/**
 * @Route("/verhuurders")
 * @Template
 */
class VerhuurdersController extends AbstractController
{
    public $title = 'Verhuurders';
    public $entityClass = Verhuurder::class;
    protected $entityName = 'verhuurder';
    protected $formClass = VerhuurderType::class;
    protected $filterFormClass = VerhuurderFilterType::class;
    protected $baseRouteName = 'odp_verhuurders_';
    protected $addMethod = "addVerhuurder";
    protected $searchFilterTypeClass = KlantFilterType::class;
    protected $searchEntity = Klant::class;


    /**
     * @var VerhuurderDaoInterface
     *
     * @DI\Inject("OdpBundle\Service\VerhuurderDao")
     */
    protected $dao;

    /**
     * @var KlantDaoInterface
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    protected $searchDao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("odp.export.verhuurders")
     */
    protected $export;


    /**
     * @Route("/old")
     */
    public function oldIndex()
    {
//        $entityManager = $this->getEntityManager();
//        $repository = $entityManager->getRepository(Verhuurder::class);
//
//        $builder = $repository->createQueryBuilder('verhuurder')
//            ->innerJoin('verhuurder.klant', 'klant')
//            ->leftJoin('klant.werkgebied', 'werkgebied')
//            ->leftJoin('verhuurder.afsluiting', 'afsluiting')
//            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
//        ;
//
//        $filter = $this->getForm(VerhuurderFilterType::class);
//        $filter->handleRequest($this->getRequest());
//        $filter->getData()->applyTo($builder);
//        if ($filter->get('download')->isClicked()) {
//            return $this->download($builder);
//        }
//
//        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
//            'defaultSortFieldName' => 'klant.achternaam',
//            'defaultSortDirection' => 'asc',
//            'sortFieldWhitelist' => $this->sortFieldWhitelist,
//        ]);
//
//        return [
//            'filter' => $filter->createView(),
//            'pagination' => $pagination,
//        ];
    }

    protected function getDownloadFilename()
    {
        $filename = sprintf('onder-de-pannen-verhuurders-%s.xlsx', (new \DateTime())->format('d-m-Y'));
        return $filename;
    }

    protected function oldDownload(QueryBuilder $builder)
    {
        ini_set('memory_limit', '512M');

        $verhuurders = $builder->getQuery()->getResult();

        $this->autoRender = false;
        $filename = sprintf('onder-de-pannen-verhuurders-%s.xlsx', (new \DateTime())->format('d-m-Y'));

        /* @var $export ExportInterface */
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
     * @Route("/oldAdd")
     */
    public function oldAdd($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        if ($this->getRequest()->query->has('klantId')) {
            $klant = new Klant();
            if ('new' !== $this->getRequest()->query->get('klantId')) {
                $klant = $entityManager->find(Klant::class, $this->getRequest()->query->get('klantId'));
            }

            $verhuurder = new Verhuurder();
            $verhuurder->setKlant($klant);

            $creationForm = $this->getForm(VerhuurderType::class, $verhuurder);
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

        $filterForm = $this->getForm(KlantFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->add('submit', SubmitType::class, ['label' => 'Verder']);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->getForm(VerhuurderSelectType::class, null, [
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

        $form = $this->getForm(VerhuurderType::class, $verhuurder);
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

        $form = $this->getForm(VerhuurderCloseType::class, $verhuurder);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Verhuurder is afgesloten.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
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

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $verhuurder->reopen();
                    $entityManager->flush();

                    $this->addFlash('success', 'Huurder is heropend.');
                } catch (\Exception $e) {
                    $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
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

        $form = $this->getForm(ConfirmationType::class);
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
