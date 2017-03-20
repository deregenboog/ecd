<?php

namespace HsBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use HsBundle\Entity\Klant;
use HsBundle\Form\KlantFilterType;
use HsBundle\Form\KlantSelectType;
use HsBundle\Form\KlantType;
use HsBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hs/dienstverleners")
 */
class DienstverlenersController extends SymfonyController
{
    use MemosAddControllerTrait, DocumentenAddControllerTrait;

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("hs.dao.klant")
     */
    private $dao;

    private $enabledFilters = [
        'openstaand',
        'klant' => ['id', 'naam', 'stadsdeel'],
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
        if ($klantId = $request->get('klantId')) {
            if ($klantId === 'new') {
                $appKlant = new AppKlant();
            } else {
                $appKlant = $this->getEntityManager()->find(AppKlant::class, $klantId);
            }

            $klant = new Klant($appKlant);
            $creationForm = $this->getForm($klant)->handleRequest($this->getRequest());

            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                try {
                    $this->dao->create($klant);
                    $this->addFlash('success', 'Klant is opgeslagen.');

                    return $this->redirectToRoute('hs_klanten_memos_add', ['id' => $klant->getId()]);
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }

                return $this->redirectToIndex();
            }

            return [
                'creationForm' => $creationForm->createView(),
            ];
        }

        $filterForm = $this->createForm(AppKlantFilterType::class, null, [
            'enabled_filters' => ['naam'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(KlantSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            return [
                'selectionForm' => $selectionForm->createView(),
            ];
        }

        if ($selectionForm->isSubmitted() && $selectionForm->isValid()) {
            $klant = $selectionForm->getData();
            if ($klant->getKlant() instanceof AppKlant) {
                $klantId = $klant->getKlant()->getId();
            } else {
                $klantId = 'new';
            }

            return $this->redirectToRoute('hs_klanten_add', ['klantId' => $klantId]);
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
                $this->addFlash('success', 'Klant is opgeslagen.');

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
            $this->addFlash('success', 'Klant is verwijderd.');

            return $this->redirectToIndex();
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    private function getFilter()
    {
        return $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
    }

    private function getForm($data = null)
    {
        return $this->createForm(KlantType::class, $data);
    }

    private function redirectToIndex()
    {
        return $this->redirectToRoute('hs_klanten_index');
    }

    private function redirectToView($id)
    {
        return $this->redirectToRoute('hs_klanten_view', ['id' => $id]);
    }
}
