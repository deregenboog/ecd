<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use MwBundle\Entity\Verslag;
use MwBundle\Form\VerslagModel;
use MwBundle\Form\VerslagType;
use MwBundle\Service\InventarisatieDao;
use MwBundle\Service\KlantDao;
use MwBundle\Service\VerslagDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/verslagen")
 * @Template
 */
class VerslagenController extends AbstractController
{
    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'mw_verslagen_';

    /**
     * @var VerslagDao
     */
    protected $dao;

    /**
     * @var KlantDao
     */
    protected $klantDao;

    /**
     * @var InventarisatieDao
     */
    protected $inventarisatieDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(VerslagDao $dao, KlantDao $klantDao, InventarisatieDao $inventarisatieDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->inventarisatieDao = $inventarisatieDao;
        $this->export = $export;
    }

    /**
     * @Route("/add/{klant}")
     * @ParamConverter("klant", class="MwBundle:Klant")
     */
    public function addAction(Request $request)
    {
        $klant = $request->get('klant');
        $entity = new Verslag($klant, Verslag::TYPE_MW);

        $formResult = $this->processForm($request, $entity);

        return $formResult;
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        return $this->processForm($request, $entity);
    }

    protected function addParams($entity, Request $request)
    {
        assert($entity instanceof Verslag);

        $event = new DienstenLookupEvent($entity->getKlant()->getId());
        if ($event->getKlantId()) {
            $this->eventDispatcher->dispatch($event, Events::DIENSTEN_LOOKUP);
        }

        return [
            'diensten' => $event->getDiensten(),
            'inventarisaties' => $this->inventarisatieDao->findAllAsTree(),
        ];
    }

    protected function processForm(Request $request, $entity = null)
    {
        $inventarisaties = $this->inventarisatieDao->findAllAsTree();
        $model = new VerslagModel($entity, $inventarisaties);

        $form = $this->getForm($this->formClass, $model, [
            'medewerker' => $this->getMedewerker(),
            'inventarisaties' => $inventarisaties,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($entity->getId()) {
                    $this->dao->update($entity);
                } else {
                    $this->dao->create($entity);
                }
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
            } catch (UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('mw_klanten_view', [
                'id' => $entity->getKlant()->getId(),
                '_fragment' => 'verslagen',
            ]);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
            'inventarisaties' => $inventarisaties,
        ];
    }
}
