<?php

namespace TwBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\Pandeigenaar;
use TwBundle\Form\PandeigenaarType;
use TwBundle\Service\PandeigenaarDaoInterface;

/**
 * @Route("/admin/pandeigenaren")
 *
 * @Template
 */
class PandeigenarenController extends SymfonyController
{
    public $title = 'Pandeigenaren';

    /**
     * @var PandeigenaarDaoInterface
     */
    private $pandeigenaarDao;

    public function __construct(PandeigenaarDaoInterface $pandeigenaarDao)
    {
        $this->pandeigenaarDao = $pandeigenaarDao;
    }

    /**
     * @Route("/")
     */
    public function index()
    {
        $page = $this->getRequest()->get('page', 1);
        $pagination = $this->pandeigenaarDao->findAll($page);

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/add")
     */
    public function add()
    {
        $pandeigenaar = new Pandeigenaar();

        $form = $this->getForm(PandeigenaarType::class, $pandeigenaar);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->pandeigenaarDao->create($pandeigenaar);
                $this->addFlash('success', 'Pandeigenaar is toegevoegd.');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('tw_pandeigenaren_index');
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
        $pandeigenaar = $this->pandeigenaarDao->find($id);

        $form = $this->getForm(PandeigenaarType::class, $pandeigenaar);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->pandeigenaarDao->update($pandeigenaar);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('tw_pandeigenaren_index');
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
        $pandeigenaar = $this->pandeigenaarDao->find($id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->pandeigenaarDao->delete($pandeigenaar);
                    $this->addFlash('success', 'Pandeigenaar is verwijderd.');
                } catch (\Exception $e) {
                    $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
                }
            }

            return $this->redirectToRoute('tw_pandeigenaren_index');
        }

        return [
            'form' => $form->createView(),
            'pandeigenaar' => $pandeigenaar,
        ];
    }
}
