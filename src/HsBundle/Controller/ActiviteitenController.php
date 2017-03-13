<?php

namespace HsBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use HsBundle\Entity\Activiteit;
use HsBundle\Form\ActiviteitType;
use HsBundle\Service\ActiviteitDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hs/admin/activiteiten")
 */
class ActiviteitenController extends SymfonyController
{
    /**
     * @var ActiviteitDaoInterface
     *
     * @DI\Inject("hs.dao.activiteit")
     */
    private $dao;

    /**
     * @Route("/")
     */
    public function index(Request $request)
    {
        $pagination = $this->dao->findAll($request->get('page', 1));

        return [
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
     * @Route("/add")
     */
    public function add()
    {
        $form = $this->getForm();
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->create($form->getData());
            $this->addFlash('success', 'Activiteit is opgeslagen.');

            return $this->redirectToIndexAction();
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ActiviteitType::class, $entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->update($entity);
            $this->addFlash('success', 'Activiteit is opgeslagen.');

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
                $this->addFlash('success', 'Activiteit is verwijderd.');
            }

            return $this->redirectToIndexAction();
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    private function getForm($data = null)
    {
        return $this->createForm(ActiviteitType::class, $data);
    }
    
    private function redirectToIndexAction()
    {
        return $this->redirectToRoute('hs_activiteiten_index');
    }

    private function redirectToViewAction($id)
    {
        return $this->redirectToRoute('hs_activiteiten_view', ['id' => $id]);
    }
}
