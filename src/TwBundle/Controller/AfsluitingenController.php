<?php

namespace TwBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Exception\UserException;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Form\AfsluitingType;
use TwBundle\Service\AfsluitingDaoInterface;

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

    public function __construct(AfsluitingDaoInterface $dao)
    {
        $this->dao = $dao;
    }

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

        $form = $this->getForm(AfsluitingType::class, $afsluiting, [
            'data_class' => $this->entityClass,
        ]);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($afsluiting);
                $this->addFlash('success', $this->entityName.' is toegevoegd.');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
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

        $form = $this->getForm(AfsluitingType::class, $afsluiting, [
            'data_class' => $this->entityClass,
        ]);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($afsluiting);
                $this->addFlash('success', $this->entityName.' is gewijzigd.');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
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

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->dao->delete($afsluiting);
                    $this->addFlash('success', $this->entityName.' is verwijderd.');
                } catch (UserException $e) {
                    //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $message = $e->getMessage();
                    $this->addFlash('danger', $message);
                    //                return $this->redirectToRoute('app_klanten_index');
                } catch (\Exception $e) {
                    $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
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
