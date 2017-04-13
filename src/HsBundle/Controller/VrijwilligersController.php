<?php

namespace HsBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use HsBundle\Entity\Vrijwilliger;
use HsBundle\Form\VrijwilligerFilterType;
use HsBundle\Form\VrijwilligerType;
use HsBundle\Service\VrijwilligerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\ConfirmationType;

/**
 * @Route("/hs/vrijwilligers")
 */
class VrijwilligersController extends SymfonyController
{
    use MemosAddControllerTrait, DocumentenAddControllerTrait;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("hs.dao.vrijwilliger")
     */
    private $dao;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("app.dao.vrijwilliger")
     */
    private $vrijwilligerDao;

    private $enabledFilters = [
        'rijbewijs',
        'vrijwilliger' => ['id', 'naam', 'stadsdeel'],
        'filter',
        'download',
    ];

    /**
     * @Route("/")
     */
    public function index(Request $request)
    {
        $form = $this->getFilter()->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();
        } else {
            $filter = null;
        }

        if ($form->get('download')->isClicked()) {
            return $this->download($filter);
        }

        $pagination = $this->dao->findAll($request->get('page', 1), $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $pagination,
        ];
    }

    private function download(FilterInterface $filter)
    {
        $collection = $this->dao->findAll(0, $filter);

        $response = $this->render('@Hs/vrijwilligers/download.csv.twig', ['collection' => $collection]);

        $filename = sprintf('homeservice-vrijwilligers-%s.xls', (new \DateTime())->format('d-m-Y'));
        $response->headers->set('Content-type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @Route("/add")
     */
    public function add(Request $request)
    {
        if ($vrijwilligerId = $request->get('vrijwilligerId')) {
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
            $creationForm = $this->getForm($vrijwilliger)->handleRequest($this->getRequest());

            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                try {
                    $this->dao->create($vrijwilliger);
                    $this->addFlash('success', 'Vrijwilliger is opgeslagen.');

                    return $this->redirectToRoute('hs_vrijwilligers_memos_add', ['id' => $vrijwilliger->getId()]);
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }

                return $this->redirectToIndex();
            }

            return [
                'creationForm' => $creationForm->createView(),
            ];
        }

        $filterForm = $this->createForm(AppVrijwilligerFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $builder = $this->getEntityManager()->getRepository(AppVrijwilliger::class)
                ->createQueryBuilder('appVrijwilliger')
                ->where('appVrijwilliger.disabled = false')
                ->orderBy('appVrijwilliger.achternaam')
            ;
            $filterForm->getData()->alias = 'appVrijwilliger';
            $filterForm->getData()->applyTo($builder);

            return [
                'vrijwilligers' => $builder->getQuery()->getResult(),
            ];
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
    }

    /**
     * @Route("/{id}/view")
     */
    public function view($id)
    {
        $entity = $this->dao->find($id);

        return [
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm($entity)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Vrijwilliger is opgeslagen.');

                return $this->redirectToView($entity);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $this->dao->delete($entity);
                $this->addFlash('success', 'Vrijwilliger is verwijderd.');
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    private function getFilter()
    {
        return $this->createForm(VrijwilligerFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
    }

    private function getForm($data = null)
    {
        return $this->createForm(VrijwilligerType::class, $data);
    }

    private function redirectToIndex()
    {
        return $this->redirectToRoute('hs_vrijwilligers_index');
    }

    private function redirectToView(Vrijwilliger $entity)
    {
        return $this->redirectToRoute('hs_vrijwilligers_view', ['id' => $entity->getId()]);
    }
}
