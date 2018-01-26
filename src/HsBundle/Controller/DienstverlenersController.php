<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Form\DienstverlenerFilterType;
use HsBundle\Form\DienstverlenerType;
use HsBundle\Service\DienstverlenerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dienstverleners")
 */
class DienstverlenersController extends AbstractController
{
    protected $title = 'Dienstverleners';
    protected $entityName = 'dienstverlener';
    protected $entityClass = Dienstverlener::class;
    protected $formClass = DienstverlenerType::class;
    protected $filterFormClass = DienstverlenerFilterType::class;
    protected $baseRouteName = 'hs_dienstverleners_';

    /**
     * @var DienstverlenerDaoInterface
     *
     * @DI\Inject("hs.dao.dienstverlener")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("hs.export.dienstverlener")
     */
    protected $export;

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    private $klantDao;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if ($request->get('klant')) {
            return $this->doAdd($request);
        }

        return $this->doSearch($request);
    }

    private function doSearch(Request $request)
    {
        $filterForm = $this->createForm(AppKlantFilterType::class, null, [
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

                return [
                    'filterForm' => $filterForm->createView(),
                ];
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

    private function doAdd(Request $request)
    {
        $klantId = $request->get('klant');
        if ('new' === $klantId) {
            $klant = new Klant();
        } else {
            $klant = $this->klantDao->find($klantId);
        }

        // redirect if already exists
        $dienstverlener = $this->dao->findOneByKlant($klant);
        if ($dienstverlener) {
            return $this->redirectToView($dienstverlener);
        }

        $dienstverlener = new Dienstverlener($klant);
        $creationForm = $this->createForm(DienstverlenerType::class, $dienstverlener);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($dienstverlener);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToRoute('hs_memos_add', [
                    'dienstverlener' => $dienstverlener->getId(),
                    'redirect' => $this->generateUrl('hs_dienstverleners_view', ['id' => $dienstverlener->getId()]).'#memos',
                ]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'creationForm' => $creationForm->createView(),
        ];
    }
}
