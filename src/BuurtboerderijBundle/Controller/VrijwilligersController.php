<?php

namespace BuurtboerderijBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use AppBundle\Export\AbstractExport;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use BuurtboerderijBundle\Entity\Vrijwilliger;
use BuurtboerderijBundle\Form\VrijwilligerCloseType;
use BuurtboerderijBundle\Form\VrijwilligerFilterType;
use BuurtboerderijBundle\Form\VrijwilligerType;
use BuurtboerderijBundle\Service\VrijwilligerDao;
use BuurtboerderijBundle\Service\VrijwilligerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/vrijwilligers")
 * @Template
 */
class VrijwilligersController extends AbstractController
{
    protected $title = 'Vrijwilligers';
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilliger::class;
    protected $formClass = VrijwilligerType::class;
    protected $filterFormClass = VrijwilligerFilterType::class;
    protected $baseRouteName = 'buurtboerderij_vrijwilligers_';

    /**
     * @var VrijwilligerDao
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     */
    protected $export;

    /**
     * @var VrijwilligerDao
     *
     */
    private $vrijwilligerDao;

    /**
     * @param VrijwilligerDao $dao
     * @param AbstractExport $export
     * @param VrijwilligerDao $vrijwilligerDao
     */
    public function __construct(VrijwilligerDao $dao, AbstractExport $export, VrijwilligerDao $vrijwilligerDao)
    {
        $this->dao = $dao;
        $this->export = $export;
        $this->vrijwilligerDao = $vrijwilligerDao;
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
        $this->formClass = VrijwilligerCloseType::class;

        if (!$entity) {
            return $this->redirectToIndex();
        }

        $entity->setAfsluitdatum(new \DateTime());

        return $this->processForm($request, $entity);
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

    protected function doSearch(Request $request)
    {
        $filterForm = $this->getForm(AppVrijwilligerFilterType::class, null, [
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
            $vrijwilliger = new \AppBundle\Entity\Vrijwilliger();
        } else {
            $vrijwilliger = $this->vrijwilligerDao->find($vrijwilligerId);
            if ($vrijwilliger) {
                // redirect if already exists
                $buurtboerderijVrijwilliger = $this->dao->findOneByVrijwilliger($vrijwilliger);
                if ($buurtboerderijVrijwilliger) {
                    return $this->redirectToView($buurtboerderijVrijwilliger);
                }
            }
        }

        $buurtboerderijVrijwilliger = new Vrijwilliger($vrijwilliger);
        $creationForm = $this->getForm(VrijwilligerType::class, $buurtboerderijVrijwilliger);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($buurtboerderijVrijwilliger);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToView($buurtboerderijVrijwilliger);
            }  catch(UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger',$message);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $buurtboerderijVrijwilliger,
            'creationForm' => $creationForm->createView(),
        ];
    }
}
