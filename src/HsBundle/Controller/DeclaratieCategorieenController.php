<?php

namespace HsBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use HsBundle\Entity\DeclaratieCategorie;
use HsBundle\Form\DeclaratieCategorieType;
use HsBundle\Service\DeclaratieCategorieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Entity\Declaratie;

/**
 * @Route("/hs/admin/declaratiecategorieen")
 */
class DeclaratieCategorieenController extends SymfonyController
{
    /**
     * @var DeclaratieCategorieDaoInterface
     *
     * @DI\Inject("hs.dao.declaratiecategorie")
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
     * @Route("/add")
     */
    public function add()
    {
        $entity = new DeclaratieCategorie();

        $form = $this->getForm($entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->create($entity);
            $this->addFlash('success', 'Declaratiecategorie is opgeslagen.');

            return $this->redirectToIndexAction();
        }

        return [
            'form' => $form->createView(),
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
    public function edit($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(DeclaratieCategorieType::class, $entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->update($entity);
            $this->addFlash('success', 'Declaratiecategorie is opgeslagen.');

            return $this->redirectToView($entity);
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
                $this->addFlash('success', 'Declaratiecategorie is verwijderd.');
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
        return $this->createForm(DeclaratieCategorieType::class, $data);
    }

    private function redirectToIndexAction()
    {
        return $this->redirectToRoute('hs_declaratiecategorieen_index');
    }

    private function redirectToView(DeclaratieCategorie $entity)
    {
        return $this->redirectToRoute('hs_declaratiecategorieen_view', ['id' => $entity->getId()]);
    }
}
