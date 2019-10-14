<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\Coordinator;
use OdpBundle\Form\CoordinatorType;
use OdpBundle\Service\CoordinatorDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/coordinatoren")
 * @Template
 */
class CoordinatorenController extends SymfonyController
{
    public $title = 'Coördinatoren';

    /**
     * @var CoordinatorDaoInterface
     *
     * @DI\Inject("OdpBundle\Service\CoordinatorDao")
     */
    private $coordinatorDao;

    /**
     * @Route("/")
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
     * @Route("/add")
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
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('odp_coordinatoren_index');
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
        $coordinator = $this->coordinatorDao->find($id);

        $form = $this->createForm(CoordinatorType::class, $coordinator);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->coordinatorDao->update($coordinator);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('odp_coordinatoren_index');
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
        $coordinator = $this->coordinatorDao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->coordinatorDao->delete($coordinator);
                    $this->addFlash('success', 'Coordinator is verwijderd.');
                } catch (\Exception $e) {
                    $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
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
