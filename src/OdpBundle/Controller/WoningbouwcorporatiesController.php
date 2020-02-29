<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use OdpBundle\Entity\Woningbouwcorporatie;
use OdpBundle\Form\WoningbouwcorporatieType;
use OdpBundle\Service\WoningbouwcorporatieDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/woningbouwcorporaties")
 * @Template
 */
class WoningbouwcorporatiesController extends SymfonyController
{
    public $title = 'Woningbouwcorporaties';

    /**
     * @var WoningbouwcorporatieDaoInterface
     */
    private $woningbouwcorporatieDao;

    public function __construct()
    {
        $this->woningbouwcorporatieDao = $this->get("OdpBundle\Service\WoningbouwcorporatieDao");
    }

    /**
     * @Route("/")
     */
    public function index()
    {
        $page = $this->getRequest()->get('page', 1);
        $pagination = $this->woningbouwcorporatieDao->findAll($page);

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/add")
     */
    public function add()
    {
        $woningbouwcorporatie = new Woningbouwcorporatie();

        $form = $this->getForm(WoningbouwcorporatieType::class, $woningbouwcorporatie);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->woningbouwcorporatieDao->create($woningbouwcorporatie);
                $this->addFlash('success', 'Woningbouwcorporatie is toegevoegd.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('odp_woningbouwcorporaties_index');
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
        $woningbouwcorporatie = $this->woningbouwcorporatieDao->find($id);

        $form = $this->getForm(WoningbouwcorporatieType::class, $woningbouwcorporatie);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->woningbouwcorporatieDao->update($woningbouwcorporatie);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('odp_woningbouwcorporaties_index');
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
        $woningbouwcorporatie = $this->woningbouwcorporatieDao->find($id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->woningbouwcorporatieDao->delete($woningbouwcorporatie);
                    $this->addFlash('success', 'Woningbouwcorporatie is verwijderd.');
                } catch (\Exception $e) {
                    $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
                }
            }

            return $this->redirectToRoute('odp_woningbouwcorporaties_index');
        }

        return [
            'form' => $form->createView(),
            'woningbouwcorporatie' => $woningbouwcorporatie,
        ];
    }
}
