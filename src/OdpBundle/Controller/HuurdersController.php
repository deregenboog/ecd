<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use Doctrine\ORM\QueryBuilder;
use OdpBundle\Entity\Huurder;
use OdpBundle\Filter\HuurderFilter;
use OdpBundle\Form\HuurderCloseType;
use OdpBundle\Form\HuurderFilterType;
use OdpBundle\Form\HuurderSelectType;
use OdpBundle\Form\HuurderType;
use OdpBundle\Service\HuurderDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;



/**
 * @Route("/huurders")
 * @Template
 */
class HuurdersController extends AbstractController
{
    public $title = 'Huurders';

    public $entityClass = Huurder::class;
    protected $entityName = 'huurder';
    protected $formClass = HuurderType::class;
    protected $filterFormClass = HuurderFilterType::class;
    protected $baseRouteName = 'odp_huurders_';
    protected $addMethod = "addHuurder";
    protected $searchFilterTypeClass = KlantFilterType::class;
    protected $searchEntity = Klant::class;

    /**
     * @var HuurderDaoInterface
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @var KlantDaoInterface
     */
    protected $searchDao;

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'werkgebied.naam',
        'huurder.automatischeIncasso',
        'huurder.inschrijvingWoningnet',
        'huurder.waPolis',
        'huurder.aanmelddatum',
        'huurder.afsluitdatum',
        'huurder.wpi',
    ];

    public function __construct()
    {
        $this->dao = $this->get("OdpBundle\Service\HuurderDao");
        $this->export = $this->get("odp.export.huurders");
        $this->searchDao = $this->get("AppBundle\Service\KlantDao");
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

            $creationForm = $this->getForm(HuurderType::class, $huurder);
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

        $filterForm = $this->getForm(KlantFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->add('submit', SubmitType::class, ['label' => 'Verder']);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->getForm(HuurderSelectType::class, null, [
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

        $form = $this->getForm(HuurderType::class, $huurder);
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

        $form = $this->getForm(HuurderCloseType::class, $huurder);
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

        $form = $this->getForm(ConfirmationType::class);
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

        $form = $this->getForm(ConfirmationType::class);
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
