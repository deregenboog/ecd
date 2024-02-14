<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Service\KlantDao;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use VillaBundle\Entity\Aanmelding;
use VillaBundle\Entity\Slaper;
use VillaBundle\Form\SlaperFilterType;
use VillaBundle\Form\SlaperType;
use VillaBundle\Service\SlaperDao;

/**
 * @Route("/slapers")
 * @Template
 */
class SlapersController extends AbstractController
{
    protected $entityName = 'slaper';
    protected $entityClass = Slaper::class;
    protected $formClass = SlaperType::class;
    protected $filterFormClass = SlaperFilterType::class;
    protected $baseRouteName = 'villa_slapers_';

    protected $searchFilterTypeClass = AppKlantFilterType::class;
    protected $searchEntity = AppKlant::class;
    protected $searchEntityName = 'appKlant';
    protected $searchDao = null;


    /**
     * @var SlaperDao
     */
    protected $dao;

    /**
     * @var KlantDao
     */
    private $klantDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param SlaperDao $dao
     * @param KlantDao $klantDao
     * @param ExportInterface $export
     */
    public function __construct(SlaperDao $dao, KlantDao $klantDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->searchDao = $klantDao;
        $this->export = $export;
    }


//    /**
//     * @Route("/add")
//     */
//    public function addAction(Request $request)
//    {
//        if ($request->get('entity')) {
//            return $this->doAdd($request);
//        }
//
//        return $this->doSearch($request);
//    }

    /**
     * @Route("/{id}/open")
     */
    public function openAction($id)
    {
        $slaper = $this->dao->find($id);

        $aanmelding = new Aanmelding();
        $slaper->addAanmelding($aanmelding);

        $form = $this->getForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($slaper);
                $this->addFlash('success', 'Het dossier is heropend.');

                return $this->redirectToRoute('oek_slapers_view', ['id' => $slaper->getId()]);
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'slaper' => $slaper,
        ];
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $slaper = $this->dao->find($id);

        $afsluiting = new Afsluiting();
        $slaper->addAfsluiting($afsluiting);

        $form = $this->getForm(AfsluitingType::class, $afsluiting);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($slaper);
                $this->addFlash('success', 'Het dossier is afgesloten.');

                return $this->redirectToRoute('oek_slapers_view', ['id' => $slaper->getId()]);
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'slaper' => $slaper,
        ];
    }

    protected function getDownloadFilename()
    {
        return sprintf('op-eigen-kracht-slapers-%s.xlsx', (new \DateTime())->format('d-m-Y'));
    }

//    protected function doSearch(Request $request)
//    {
//        $filterForm = $this->getForm(KlantFilterType::class, null, [
//            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
//        ]);
//        $filterForm->handleRequest($request);
//
//        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
//            $count = (int) $this->klantDao->countAll($filterForm->getData());
//            if (0 === $count) {
//                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));
//
//                return $this->redirectToRoute($this->baseRouteName.'add', ['klant' => 'new']);
//            }
//
//            if ($count > 100) {
//                $filterForm->addError(new FormError('De zoekopdracht leverde teveel resultaten op. Probeer het opnieuw met een specifiekere zoekopdracht.'));
//
//                return [
//                    'filterForm' => $filterForm->createView(),
//                ];
//            }
//
//            return [
//                'filterForm' => $filterForm->createView(),
//                'klanten' => $this->klantDao->findAll(null, $filterForm->getData()),
//            ];
//        }
//
//        return [
//            'filterForm' => $filterForm->createView(),
//        ];
//    }

//    protected function doAdd(Request $request)
//    {
//        $klantId = $request->get('klant');
//        if ('new' === $klantId) {
//            $klant = new Klant();
//        } else {
//            $klant = $this->klantDao->find($klantId);
//        }
//
//        // redirect if already exists
//        $slaper = $this->dao->findOneByKlant($klant);
//        if ($slaper) {
//            return $this->redirectToView($slaper);
//        }
//
//        $slaper = new Slaper($klant);
//        $creationForm = $this->getForm(SlaperType::class, $slaper);
//        $creationForm->handleRequest($request);
//
//        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
//            try {
//                $this->dao->create($slaper);
//                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
//
//                return $this->redirectToRoute('oek_slapers_view', ['id' => $slaper->getId()]);
//            } catch(UserException $e) {
////                $this->logger->error($e->getMessage(), ['exception' => $e]);
//                $message =  $e->getMessage();
//                $this->addFlash('danger', $message);
////                return $this->redirectToRoute('app_klanten_index');
//            } catch (\Exception $e) {
//                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
//                $this->addFlash('danger', $message);
//            }
//
//            return $this->redirectToIndex();
//        }
//
//        return [
//            'creationForm' => $creationForm->createView(),
//        ];
//    }
}
