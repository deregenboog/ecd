<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Form\IzVrijwilligerFilterType;
use IzBundle\Service\VrijwilligerDaoInterface;
use IzBundle\Form\IzVrijwilligerType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\VrijwilligerFilterType;
use AppBundle\Entity\Vrijwilliger;
use Symfony\Component\Form\FormError;
use IzBundle\Form\IzDeelnemerCloseType;

/**
 * @Route("/vrijwilligers")
 */
class VrijwilligersController extends AbstractController
{
    protected $title = 'Vrijwilligers';
    protected $entityName = 'vrijwilliger';
    protected $entityClass = IzVrijwilliger::class;
    protected $formClass = IzVrijwilligerType::class;
    protected $filterFormClass = IzVrijwilligerFilterType::class;
    protected $baseRouteName = 'iz_vrijwilligers_';

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("IzBundle\Service\VrijwilligerDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.vrijwilligers")
     */
    protected $export;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("AppBundle\Service\VrijwilligerDao")
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

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if (count($entity->getOpenHulpaanbiedingen()) > 0
            || count($entity->getActieveKoppelingen()) > 0
        ) {
            $this->addFlash('danger', 'Dit dossier kan niet worden afgesloten omdat er nog open hulpaanbiedingen en/of actieve koppelingen zijn.');

            return $this->redirectToView($entity);
        }

        $this->formClass = IzDeelnemerCloseType::class;

        return $this->processForm($request, $entity);
    }

    private function doSearch(Request $request)
    {
        $filterForm = $this->createForm(VrijwilligerFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $count = (int) $this->vrijwilligerDao->countAll($filterForm->getData());
            if (0 === $count) {
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
        if ('new' === $vrijwilligerId) {
            $vrijwilliger = new Vrijwilliger();
        } else {
            $vrijwilliger = $this->vrijwilligerDao->find($vrijwilligerId);
            if ($vrijwilliger) {
                // redirect if already exists
                $izVrijwilliger = $this->dao->findOneByVrijwilliger($vrijwilliger);
                if ($izVrijwilliger) {
                    return $this->redirectToView($izVrijwilliger);
                }
            }
        }

        $izVrijwilliger = new IzVrijwilliger($vrijwilliger);
        $creationForm = $this->createForm(IzVrijwilligerType::class, $izVrijwilliger);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($izVrijwilliger);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToView($izVrijwilliger);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $izVrijwilliger,
            'creationForm' => $creationForm->createView(),
        ];
    }
}
