<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Export\AbstractExport;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use IzBundle\Entity\IzKlant;
use IzBundle\Form\IzDeelnemerCloseType;
use IzBundle\Form\IzKlantFilterType;
use IzBundle\Form\IzKlantType;
use IzBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/klanten")
 * @Template
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

        if (!$entity) {
            return $this->redirectToIndex();
        }

        if (!$entity->isCloseable()) {
            $this->addFlash('danger', 'Dit dossier kan niet worden afgesloten omdat er nog open hulpvragen en/of actieve koppelingen zijn.');

            return $this->redirectToView($entity);
        }

        $event = new GenericEvent($entity->getKlant(), ['messages' => []]);
        $this->get('event_dispatcher')->dispatch(Events::BEFORE_CLOSE, $event);

        return array_merge(
            $this->processForm($request, $entity),
            ['messages' => $event->getArgument('messages')]
        );
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopenAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entity->reopen();
                $this->dao->update($entity);

                $this->addFlash('success', ucfirst($this->entityName).' is heropend.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
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

    protected function addParams($entity, Request $request)
    {
        assert($entity instanceof IzKlant);

        $event = new DienstenLookupEvent($entity->getKlant()->getId(), []);
        if ($event->getKlantId()) {
            $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);
        }

        return [
            'diensten' => $event->getDiensten(),
        ];
    }
}
