<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Factuur;
use AppBundle\Form\ConfirmationType;
use HsBundle\Entity\Klus;
use HsBundle\Form\FactuurFilterType;
use AppBundle\Controller\SymfonyController;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Service\FactuurDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/hs/facturen")
 */
class FacturenController extends SymfonyController
{
    /**
     * @var FactuurDaoInterface
     *
     * @DI\Inject("hs.dao.factuur")
     */
    private $dao;

    private $enabledFilters = [
        'nummer',
        'datum',
        'bedrag',
        'openstaand',
        'klant' => ['naam'],
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
     * @Route("/add/{klus}")
     * @ParamConverter()
     */
    public function add(Klus $klus)
    {
        $factuur = new Factuur($klus);
        if (count($factuur->getRegistraties()) > 0) {
            $this->dao->create($factuur);
            $this->addFlash('success', 'Factuur is toegevoegd.');
        } else {
            $this->addFlash('info', 'Er is voor de vorige maand niks te factureren.');
        }

        return $this->redirectToRoute('hs_klussen_view', ['id' => $klus->getId()]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm($entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->update($entity);
            $this->addFlash('success', 'Factuur is opgeslagen.');

            return $this->redirectToViewAction($entity->getId());
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
                $this->addFlash('success', 'Factuur is verwijderd.');
            }

            return $this->redirectToIndexAction();
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    private function getFilter()
    {
        return $this->createForm(FactuurFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
    }

    private function getForm($data = null)
    {
        return $this->createForm(BetalingType::class, $data);
    }

    private function redirectToIndexAction()
    {
        return $this->redirectToRoute('hs_facturen_index');
    }

    private function redirectToViewAction($id)
    {
        return $this->redirectToRoute('hs_facturen_view', ['id' => $id]);
    }
}
