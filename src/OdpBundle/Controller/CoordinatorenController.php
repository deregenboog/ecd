<?php

namespace OdpBundle\Controller;

use OdpBundle\Entity\Coordinator;
use OdpBundle\Service\CoordinatorDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use OdpBundle\Form\CoordinatorType;
use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;

class CoordinatorenController extends SymfonyController
{
    /**
     * @var CoordinatorDaoInterface
     *
     * @DI\Inject("odp.dao.coordinator")
     */
    private $coordinatorDao;

    /**
     * @Route("/odp/admin/coordinatoren")
     */
    public function index()
    {
        $page = $this->getRequest()->get('page', 1);
        $pagination = $this->coordinatorDao->findAll($page);

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/odp/admin/coordinatoren/add")
     */
    public function add()
    {
        $coordinator = new Coordinator();

        $form = $this->createForm(CoordinatorType::class, $coordinator);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->coordinatorDao->create($coordinator);
                $this->addFlash('success', 'Coordinator is toegevoegd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_coordinatoren_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/odp/admin/coordinatoren/{id}/edit")
     */
    public function edit($id)
    {
        $coordinator = $this->coordinatorDao->find($id);

        $form = $this->createForm(CoordinatorType::class, $coordinator);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->coordinatorDao->update($coordinator);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_coordinatoren_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/odp/admin/coordinatoren/{id}/delete")
     */
    public function delete($id)
    {
        $coordinator = $this->coordinatorDao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->coordinatorDao->delete($coordinator);
                    $this->addFlash('success', 'Coordinator is verwijderd.');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }
            }

            return $this->redirectToRoute('odp_coordinatoren_index');
        }

        return [
            'form' => $form->createView(),
            'coordinator' => $coordinator,
        ];
    }
}
