<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Export\ExportInterface;
use Doctrine\ORM\EntityNotFoundException;
use MwBundle\Entity\Verslag;
use MwBundle\Form\VerslagModel;
use MwBundle\Form\VerslagType;
use MwBundle\Service\InventarisatieDaoInterface;
use MwBundle\Service\VerslagDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/verslagen")
 * @Template
 */
class VerslagenController extends AbstractController
{
    protected $title = 'Verslagen';
    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'inloop_verslagen_';

    /**
     * @var VerslagDaoInterface
     */
    protected $dao;

    /**
     * @var InventarisatieDaoInterface
     */
    protected $inventarisatieDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct()
    {
        $this->dao = $this->get("InloopBundle\Service\VerslagDao");
        $this->inventarisatieDao = $this->get("MwBundle\Service\InventarisatieDao");
        $this->export = $this->get("mw.export.klanten");
    }

    /**
     * @Route("/add/{klant}")
     * @ParamConverter("klant", class="AppBundle:Klant")
     */
    public function addAction(Request $request)
    {
        $klant = $request->get('klant');
//        $type = $request->get('type');

        $entity = new Verslag($klant, 2);
//        $entity->setType($type);

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        /** Alleen mensen van MW mogen dat soort verslagen bewerken. Mocht iemand de URL manipuleren....  */
        if($entity->getType() == 1 && !$this->isGranted("ROLE_MW")) throw new EntityNotFoundException("Kan verslag niet vinden.");
        return $this->processForm($request, $entity);
    }

    protected function addParams($entity, Request $request)
    {
        assert($entity instanceof Verslag);

        $event = new DienstenLookupEvent($entity->getKlant()->getId());
        if ($event->getKlantId()) {
            $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);
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
            } catch (\Exception $e) {
                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
            'inventarisaties' => $inventarisaties,
        ];
    }
}
