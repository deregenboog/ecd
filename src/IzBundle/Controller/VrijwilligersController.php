<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Event\Events;
use AppBundle\Export\AbstractExport;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\VrijwilligerFilterType;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Form\IzDeelnemerCloseType;
use IzBundle\Form\IzVrijwilligerFilterType;
use IzBundle\Form\IzVrijwilligerType;
use IzBundle\Service\VrijwilligerDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/vrijwilligers")
 * @Template
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
     */
    protected $dao;

    /**
     * @var AbstractExport
     */
    protected $export;

    /**
     * @var VrijwilligerDaoInterface
     */
    private $vrijwilligerDao;

    public function __construct()
    {
        $this->dao = $this->get("IzBundle\Service\VrijwilligerDao");
        $this->export = $this->get("iz.export.vrijwilligers");
        $this->vrijwilligerDao = $this->get("AppBundle\Service\VrijwilligerDao");
    }

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
        $this->formClass = IzDeelnemerCloseType::class;

        if (!$entity) {
            return $this->redirectToIndex();
        }

        if (!$entity->isCloseable()) {
            $this->addFlash('danger', 'Dit dossier kan niet worden afgesloten omdat er nog open hulpaanbiedingen en/of actieve koppelingen zijn.');

            return $this->redirectToView($entity);
        }

        $response = $this->processForm($request, $entity);
        if ($response instanceof Response) {
            return $response;
        }

        $event = new GenericEvent($entity->getVrijwilliger(), ['messages' => []]);
        $this->get('event_dispatcher')->dispatch(Events::BEFORE_CLOSE, $event);

        return array_merge($response, ['messages' => $event->getArgument('messages')]);
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopenAction(Request $request, $id)
    {
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

    private function doSearch(Request $request)
    {
        $filterForm = $this->getForm(VrijwilligerFilterType::class, null, [
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

    protected function doAdd(Request $request)
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
        $creationForm = $this->getForm(IzVrijwilligerType::class, $izVrijwilliger);
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
