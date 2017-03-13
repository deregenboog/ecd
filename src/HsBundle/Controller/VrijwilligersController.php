<?php

namespace HsBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use HsBundle\Entity\Vrijwilliger;
use HsBundle\Form\VrijwilligerFilterType;
use HsBundle\Form\VrijwilligerType;
use HsBundle\Service\VrijwilligerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Form\VrijwilligerSelectType;

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

    private $enabledFilters = [
        'dragend',
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
        $filter = $this->getFilter()->handleRequest($request);
        if ($filter->isSubmitted() && $filter->isValid()) {
            $pagination = $this->dao->findAll($request->get('page', 1), $filter->getData());
        } else {
            $pagination = $this->dao->findAll($request->get('page', 1));
        }

        return [
            'filter' => $filter->createView(),
            'pagination' => $pagination,
        ];
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

            $vrijwilliger = new Vrijwilliger($appVrijwilliger);
            $creationForm = $this->getForm($vrijwilliger);
            $creationForm->handleRequest($this->getRequest());

            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                try {
                    $this->dao->create($vrijwilliger);
                    $this->addFlash('success', 'Vrijwilliger is opgeslagen.');

                    return $this->redirectToView($vrijwilliger->getId());
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
            'enabled_filters' => ['naam'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(VrijwilligerSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            return [
                'selectionForm' => $selectionForm->createView(),
            ];
        }

        if ($selectionForm->isSubmitted() && $selectionForm->isValid()) {
            $vrijwilliger = $selectionForm->getData();
            if ($vrijwilliger->getVrijwilliger() instanceof AppVrijwilliger) {
                $vrijwilligerId = $vrijwilliger->getVrijwilliger()->getId();
            } else {
                $vrijwilligerId = 'new';
            }

            return $this->redirectToRoute('hs_vrijwilligers_add', ['vrijwilligerId' => $vrijwilligerId]);
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

                return $this->redirectToView($entity->getId());
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
            $this->dao->delete($entity);
            $this->addFlash('success', 'Vrijwilliger is verwijderd.');

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

    private function redirectToView($id)
    {
        return $this->redirectToRoute('hs_vrijwilligers_view', ['id' => $id]);
    }
}
