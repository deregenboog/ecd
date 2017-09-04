<?php

namespace HsBundle\Controller;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use HsBundle\Entity\Vrijwilliger;
use HsBundle\Form\VrijwilligerFilterType;
use HsBundle\Form\VrijwilligerType;
use HsBundle\Service\VrijwilligerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use Symfony\Component\Form\FormError;

/**
 * @Route("/vrijwilligers")
 */
class VrijwilligersController extends AbstractController
{
    protected $title = 'Vrijwilligers';
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilliger::class;
    protected $formClass = VrijwilligerType::class;
    protected $filterFormClass = VrijwilligerFilterType::class;
    protected $baseRouteName = 'hs_vrijwilligers_';

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("hs.dao.vrijwilliger")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("hs.export.vrijwilliger")
     */
    protected $export;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("app.dao.vrijwilliger")
     */
    private $vrijwilligerDao;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if ($request->get('vrijwilliger')) {
            return $this->doAdd($request);
        }

        return $this->doSearch($request);
    }

    private function doSearch(Request $request)
    {
        $filterForm = $this->createForm(AppVrijwilligerFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {

            $count = (int) $this->vrijwilligerDao->countAll($filterForm->getData());
            if ($count === 0) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['vrijwilliger' => 'new']);
            }

            if ($count > 100) {
                $filterForm->addError(new FormError('De zoekopdracht leverde teveel resultaten op. Probeer het opnieuw met een specifiekere zoekopdracht.'));
            }

            return [
                'filterForm' => $filterForm->createView(),
                'vrijwilligers' => $this->vrijwilligerDao->findAll(null, $filterForm->getData()),
            ];
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
    }

    private function doAdd(Request $request)
    {
        $vrijwilligerId = $request->get('vrijwilliger');
        if ($vrijwilligerId === 'new') {
            $appVrijwilliger = new AppVrijwilliger();
        } else {
            $appVrijwilliger = $this->getEntityManager()->find(AppVrijwilliger::class, $vrijwilligerId);
        }

        // redirect if already exists
        $vrijwilliger = $this->dao->findOneByVrijwilliger($appVrijwilliger);
        if ($vrijwilliger) {
            return $this->redirectToView($vrijwilliger);
        }

        $vrijwilliger = new Vrijwilliger($appVrijwilliger);
        $creationForm = $this->createForm(VrijwilligerType::class, $vrijwilliger);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($vrijwilliger);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToRoute('hs_memos_add', [
                    'vrijwilliger' => $vrijwilliger->getId(),
                    'redirect' => $this->generateUrl('hs_vrijwilligers_view', ['id' => $vrijwilliger->getId()]).'#memos',
                ]);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToIndex();
        }

        return [
            'creationForm' => $creationForm->createView(),
        ];
    }
}
