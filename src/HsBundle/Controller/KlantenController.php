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
use AppBundle\Entity\Medewerker;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Memo;

/**
 * @Route("/hs/klanten")
 */
class KlantenController extends SymfonyController
{
    use MemosAddControllerTrait, DocumentenAddControllerTrait;

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("hs.dao.klant")
     */
    private $dao;

    private $enabledFilters = [
        'id',
        'naam',
        'stadsdeel',
        'actief',
        'negatiefSaldo',
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
        $klant = new Klant();
        $form = $this->getForm($klant)->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($klant);
                $this->addFlash('success', 'Klant is opgeslagen.');

                return $this->redirectToRoute('hs_klanten_memos_add', ['id' => $klant->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.'.$e->getMessage());
            }

            return $this->redirectToIndex();
        }

        return [
            'creationForm' => $form->createView(),
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
                $this->addFlash('success', 'Klant is verwijderd.');
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

    private function redirectToView(Klant $entity)
    {
        return $this->redirectToRoute('hs_klanten_view', ['id' => $entity->getId()]);
    }
}
