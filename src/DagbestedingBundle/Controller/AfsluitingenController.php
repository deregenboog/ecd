<?php

namespace DagbestedingBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use DagbestedingBundle\Form\AfsluitingType;
use DagbestedingBundle\Service\AfsluitingDaoInterface;

abstract class AfsluitingenController extends SymfonyController
{
    /**
     * @var AfsluitingDaoInterface
     */
    protected $dao;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $indexRouteName;

    /**
     * @Route("/")
     */
    public function index()
    {
        $page = $this->getRequest()->get('page', 1);
        $pagination = $this->dao->findAll($page);

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/add")
     */
    public function add()
    {
        $afsluiting = new $this->entityClass();

        $form = $this->createForm(AfsluitingType::class, $afsluiting, [
            'data_class' => $this->entityClass,
        ]);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($afsluiting);
                $this->addFlash('success', $this->entityName.' is toegevoegd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute($this->indexRouteName);
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
        $afsluiting = $this->dao->find($id);

        $form = $this->createForm(AfsluitingType::class, $afsluiting, [
            'data_class' => $this->entityClass,
        ]);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($afsluiting);
                $this->addFlash('success', $this->entityName.' is gewijzigd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute($this->indexRouteName);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $afsluiting = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->dao->delete($afsluiting);
                    $this->addFlash('success', $this->entityName.' is verwijderd.');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }
            }

            return $this->redirectToRoute($this->indexRouteName);
        }

        return [
            'form' => $form->createView(),
            'afsluiting' => $afsluiting,
        ];
    }
}
