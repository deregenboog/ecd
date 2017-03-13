<?php

namespace OdpBundle\Controller;

use OdpBundle\Entity\Verhuurderafsluiting;
use OdpBundle\Service\VerhuurderafsluitingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use OdpBundle\Form\VerhuurderafsluitingType;
use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;

class VerhuurderafsluitingenController extends SymfonyController
{
    /**
     * @var VerhuurderafsluitingDaoInterface
     *
     * @DI\Inject("odp.dao.verhuurderafsluiting")
     */
    private $verhuurderafsluitingDao;

    /**
     * @Route("/odp/admin/verhuurderafsluitingen")
     */
    public function index()
    {
        $page = $this->getRequest()->get('page', 1);
        $pagination = $this->verhuurderafsluitingDao->findAll($page);

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/odp/admin/verhuurderafsluitingen/add")
     */
    public function add()
    {
        $verhuurderafsluiting = new Verhuurderafsluiting();

        $form = $this->createForm(VerhuurderafsluitingType::class, $verhuurderafsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->verhuurderafsluitingDao->create($verhuurderafsluiting);
                $this->addFlash('success', 'Verhuurderafsluiting is toegevoegd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_verhuurderafsluitingen_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/odp/admin/verhuurderafsluitingen/{id}/edit")
     */
    public function edit($id)
    {
        $verhuurderafsluiting = $this->verhuurderafsluitingDao->find($id);

        $form = $this->createForm(VerhuurderafsluitingType::class, $verhuurderafsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->verhuurderafsluitingDao->update($verhuurderafsluiting);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_verhuurderafsluitingen_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/odp/admin/verhuurderafsluitingen/{id}/delete")
     */
    public function delete($id)
    {
        $verhuurderafsluiting = $this->verhuurderafsluitingDao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->verhuurderafsluitingDao->delete($verhuurderafsluiting);
                    $this->addFlash('success', 'Verhuurderafsluiting is verwijderd.');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }
            }

            return $this->redirectToRoute('odp_verhuurderafsluitingen_index');
        }

        return [
            'form' => $form->createView(),
            'verhuurderafsluiting' => $verhuurderafsluiting,
        ];
    }
}
