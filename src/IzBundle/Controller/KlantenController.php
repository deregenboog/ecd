<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Exception\UserException;
use AppBundle\Export\AbstractExport;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use IzBundle\Entity\IzKlant;
use IzBundle\Form\IzDeelnemerCloseType;
use IzBundle\Form\IzKlantFilterType;
use IzBundle\Form\IzKlantType;
use IzBundle\Service\KlantDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     */
    protected $dao;

    /**
     * @var \AppBundle\Service\KlantDaoInterface
     */
    private $klantDao;

    /**
     * @var AbstractExport
     */
    protected $export;

    public function __construct(KlantDaoInterface $dao, \AppBundle\Service\KlantDaoInterface $klantDao, AbstractExport $export)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->export = $export;
    }

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
     * @Route("/{documentId}/deleteDocument")
     */
    public function deleteDocumentAction(Request $request,$documentId)
    {
        $klant = $this->dao->findKlantByDocId($documentId);
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

        $response = $this->processForm($request, $entity);
        if ($response instanceof Response) {
            return $response;
        }

        $event = new GenericEvent($entity->getKlant(), ['messages' => []]);
        $this->eventDispatcher->dispatch($event, Events::BEFORE_CLOSE);

        return array_merge($response, ['messages' => $event->getArgument('messages')]);
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopenAction(Request $request, $id)
    {
        $this->getDoctrine()->getManager()->getFilters()->disable("foutieve_invoer");
        $entity = $this->dao->find($id);

        $form = $this->getForm(ConfirmationType::class);
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

    protected function doSearch(Request $request)
    {
        $filterForm = $this->getForm(KlantFilterType::class, null, [
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
        $klantId = $request->get('klant');
        if ('new' === $klantId) {
            $klant = new Klant();
        } else {
            $klant = $this->klantDao->find($klantId);
            if ($klant) {
                // redirect if already exists
                $izKlant = $this->dao->findOneByKlant($klant);
                if ($izKlant) {
                    if($izKlant->isAfgesloten())
                    {
                        return $this->redirectToRoute("iz_klanten_reopen",["id"=>$izKlant->getId()]);
                    }
                    return $this->redirectToView($izKlant);
                }
            }
        }

        $izKlant = new IzKlant($klant);
        $creationForm = $this->getForm(IzKlantType::class, $izKlant);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($izKlant);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToView($izKlant);
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $izKlant,
            'creationForm' => $creationForm->createView(),
        ];
    }

    protected function addParams($entity, Request $request): array
    {
        assert($entity instanceof IzKlant);

        $event = new DienstenLookupEvent($entity->getKlant()->getId(), []);
        if ($event->getKlantId()) {
            $this->eventDispatcher->dispatch($event, Events::DIENSTEN_LOOKUP);
        }

        return [
            'diensten' => $event->getDiensten(),
        ];
    }
}
