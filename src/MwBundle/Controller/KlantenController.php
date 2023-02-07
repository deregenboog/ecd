<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Service\KlantDao as AppKlantDao;
use JMS\DiExtraBundle\Annotation as DI;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use MwBundle\Entity\DossierStatus;
use MwBundle\Entity\Klant;
use MwBundle\Form\AanmeldingType;
use MwBundle\Form\AfsluitingType;
use MwBundle\Form\KlantFilterType;
use MwBundle\Form\KlantType;
use MwBundle\Service\DossierStatusFactory;
use MwBundle\Service\KlantDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'mw_klanten_';
    protected $searchFilterTypeClass = AppKlantFilterType::class;

    /**
     * @var KlantDao
     */
    protected $dao;

    /**
     * @var AppKlantDao
     */
    protected $klantDao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("mw.export.klanten")
     */
    protected $export;

    public function __construct(KlantDao $dao, AppKlantDao $klantDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->export = $export;
    }

    /**
     * @Route("/add/{klant?}")
     * @ParamConverter("klant", class="AppBundle:Klant")
     */
    public function addAction(Request $request)
    {
        if ($request->get('klant')) {
            return $this->doAdd($request);
        }

        return $this->doSearch($request);
    }

    /**
     * @Route("/wachtlijst")
     * @Template("@Mw/klanten/index.html.twig")
     */
    public function wachtlijstAction(Request $request)
    {
        return $this->indexAction($request);
    }

    protected function doSearch(Request $request)
    {
        $filterForm = $this->getForm(AppKlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $count = (int) $this->klantDao->countAll($filterForm->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['klant' => 'new']);
            }

            if ($count > 100) {
                $filterForm->addError(new FormError('De zoekopdracht leverde teveel resultaten op. Probeer het opnieuw met een specifiekere zoekopdracht.'));
            }

            return [
                'filterForm' => $filterForm->createView(),
                'klanten' => $this->klantDao->findAll(null, $filterForm->getData()),
            ];
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
    }

    protected function doAdd(Request $request)
    {
        $appKlant = $request->get('klant');

        $klant = $this->dao->findOneByKlant($appKlant);
        if ($klant) {
            return $this->redirectToView($klant);
        }

        $klant = new Klant($appKlant, $this->getMedewerker());
        $this->dao->create($klant);

        return $this->redirectToRoute('mw_aanmeldingen_add', ['klant' => $klant->getId()]);
    }

    /**
     * @Route("/{klant}/close")
     * @ParamConverter("klant", class="MwBundle:Klant")
     */
    public function closeAction(Request $request, Klant $klant)
    {
        $afsluiting = new Afsluiting($this->getMedewerker());

        $form = $this->getForm(AfsluitingType::class, $afsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($afsluiting);
                $entityManager->flush();

                $this->addFlash('success', 'Mw dossier is afgesloten');
            } catch (UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
            if (array_key_exists('inloopSluiten', $request->get('afsluiting'))) {
                if ($klant->getHuidigeStatus() instanceof \InloopBundle\Entity\Aanmelding) {
                    return $this->redirectToRoute('inloop_klanten_close', ['id' => $id, 'redirect' => $this->generateUrl('mw_klanten_index')]);
                }
            }
            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('mw_klanten_index');
        }

        return [
            'klant' => $klant,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/open")
     */
    public function openAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $aanmelding = new Aanmelding($klant, $this->getMedewerker());

        $form = $this->getForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($aanmelding);
                $entityManager->flush();

                $this->addFlash('success', 'Mw dossier is heropend');
            } catch (UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('mw_klanten_index');
        }

        return [
            'klant' => $klant,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/dossierstatussen/add")
     * @ParamConverter("klant", class="MwBundle:Klant")
     * @Template
     */
    public function dossierstatussenAddAction(Request $request, Klant $klant)
    {
        $status = DossierStatusFactory::getDossierStatus($klant, $this->getMedewerker());
        $formType = $this->getFormType($status);

        $form = $this->getForm($formType, $status);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($status);
                $entityManager->flush();

                $this->addFlash('success', 'Mw dossier is heropend');
            } catch (UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('mw_klanten_view', ['id' => $klant->getId()]);
        }

        return [
            'klant' => $klant,
            'form' => $form->createView(),
        ];
    }

    private function getFormType(DossierStatus $status): string
    {
        switch (true) {
            case $status instanceof Aanmelding:
                return AanmeldingType::class;
            default:
                throw new \LogicException();
        }
    }
}
