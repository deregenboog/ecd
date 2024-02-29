<?php

namespace TwBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Exception\UserException;
use AppBundle\Form\ConfirmationType;
use TwBundle\Entity\Coordinator;
use TwBundle\Form\CoordinatorType;
use TwBundle\Service\CoordinatorDaoInterface;
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
     */
    private $coordinatorDao;

    public function __construct(CoordinatorDaoInterface $coordinatorDao)
    {
        $this->coordinatorDao = $coordinatorDao;
    }

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

        $form = $this->getForm(CoordinatorType::class, $coordinator);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->coordinatorDao->create($coordinator);
                $this->addFlash('success', 'Coordinator is toegevoegd.');
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('tw_coordinatoren_index');
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

        $form = $this->getForm(CoordinatorType::class, $coordinator);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->coordinatorDao->update($coordinator);
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('tw_coordinatoren_index');
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

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->coordinatorDao->delete($coordinator);
                    $this->addFlash('success', 'Coordinator is verwijderd.');
                } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $message =  $e->getMessage();
                    $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
                } catch (\Exception $e) {
                    $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
                }
            }

            return $this->redirectToRoute('tw_coordinatoren_index');
        }

        return [
            'form' => $form->createView(),
            'coordinator' => $coordinator,
        ];
    }
}
