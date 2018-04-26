<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Entity\IzKlant;
use IzBundle\Service\KlantDaoInterface;
use AppBundle\Export\AbstractExport;
use IzBundle\Form\IzKlantFilterType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\KlantFilterType;
use AppBundle\Entity\Klant;
use IzBundle\Form\IzKlantType;
use AppBundle\Event\Events;
use AppBundle\Event\DienstenLookupEvent;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Form\IzDeelnemerCloseType;
use Symfony\Component\Form\FormError;

/**
 * @Route("/klanten")
 */
class KlantenController extends AbstractController
{
    protected $title = 'Deelnemers';
    protected $entityName = 'deelnemer';
    protected $entityClass = IzKlant::class;
    protected $formClass = IzKlantType::class;
    protected $filterFormClass = IzKlantFilterType::class;
    protected $baseRouteName = 'iz_klanten_';

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("IzBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.klanten")
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

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $this->formClass = IzDeelnemerCloseType::class;

        return $this->processForm($request, $entity);
    }

    private function doSearch(Request $request)
    {
        $filterForm = $this->createForm(KlantFilterType::class, null, [
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

    private function doAdd(Request $request)
    {
        $klantId = $request->get('klant');
        if ('new' === $klantId) {
            $klant = new Klant();
        } else {
            $klant = $this->klantDao->find($klantId);
            if ($klant) {
                // redirect if already exists
                $izKlant = $this->dao->findOneByKlant($klant);
                if ($izKlant) {
                    return $this->redirectToView($izKlant);
                }
            }
        }

        $izKlant = new IzKlant($klant);
        $creationForm = $this->createForm(IzKlantType::class, $izKlant);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($izKlant);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToView($izKlant);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $izKlant,
            'creationForm' => $creationForm->createView(),
        ];
    }

    protected function addParams(IzKlant $entity)
    {
        $event = new DienstenLookupEvent($entity->getKlant()->getId(), []);
        $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);

        return [
            'diensten' => $event->getDiensten(),
        ];
    }
}
