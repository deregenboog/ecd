<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use AppBundle\Service\VrijwilligerDaoInterface as AppVrijwilligerDaoInterface;
use OekraineBundle\Entity\Vrijwilliger;
use OekraineBundle\Form\VrijwilligerCloseType;
use OekraineBundle\Form\VrijwilligerFilterType;
use OekraineBundle\Form\VrijwilligerType;
use OekraineBundle\Service\VrijwilligerDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vrijwilligers")
 *
 * @Template
 */
class VrijwilligersController extends AbstractController
{
    protected $title = 'Vrijwilligers';
    protected $formClassClose = VrijwilligerCloseType::class;
    protected $disabledActions = ['delete'];

    protected AppVrijwilligerDaoInterface $vrijwilligerDao;

    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilliger::class;
    protected $formClass = VrijwilligerType::class;
    protected $filterFormClass = VrijwilligerFilterType::class;
    protected $baseRouteName = 'oekraine_vrijwilligers_';


    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(VrijwilligerDaoInterface $dao, AppVrijwilligerDaoInterface $vrijwilligerDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->vrijwilligerDao = $vrijwilligerDao;
        $this->export = $export;
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
     * @Route("/close/{id}")
     */
    public function closeAction(Request $request, $id)
    {
        $this->formClass = $this->formClassClose;

        $entity = $this->dao->find($id);
        $entity->setAfsluitdatum(new \DateTime());

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/open/{id}")
     */
    public function openAction(Request $request, $id)
    {
        //        $this->formClass = VrijwilligerCloseType::class;

        $entity = $this->dao->find($id);
        $entity->setAfsluitdatum(null);
        $entity->setAfsluitreden(null);

        $this->dao->update($entity);

        return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $id]);
    }

    protected function getDownloadFilename()
    {
        return sprintf($this->baseRouteName.'%s.xlsx', (new \DateTime())->format('d-m-Y'));
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

                return [
                    'filterForm' => $filterForm->createView(),
                ];
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
            $appVrijwilliger = new AppVrijwilliger();
        } else {
            $appVrijwilliger = $this->getEntityManager()->find(AppVrijwilliger::class, $vrijwilligerId);
        }

        // redirect if already exists
        $vrijwilliger = $this->dao->findOneByVrijwilliger($appVrijwilliger);
        if ($vrijwilliger) {
            return $this->redirectToView($vrijwilliger);
        }

        $vrijwilliger = new $this->entityClass($appVrijwilliger);
        $creationForm = $this->getForm($this->formClass, $vrijwilliger);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($vrijwilliger);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $vrijwilliger->getId()]);
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'creationForm' => $creationForm->createView(),
        ];
    }
}
