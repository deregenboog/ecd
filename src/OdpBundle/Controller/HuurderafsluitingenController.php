<?php

namespace OdpBundle\Controller;

use OdpBundle\Entity\Huurderafsluiting;
use OdpBundle\Service\HuurderafsluitingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use OdpBundle\Form\HuurderafsluitingType;
use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;

class HuurderafsluitingenController extends SymfonyController
{
    /**
     * @var HuurderafsluitingDaoInterface
     *
     * @DI\Inject("odp.dao.huurderafsluiting")
     */
    private $huurderafsluitingDao;

    /**
     * @Route("/odp/admin/huurderafsluitingen")
     */
    public function index()
    {
        $page = $this->getRequest()->get('page', 1);
        $pagination = $this->huurderafsluitingDao->findAll($page);

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/odp/admin/huurderafsluitingen/add")
     */
    public function add()
    {
        $huurderafsluiting = new Huurderafsluiting();

        $form = $this->createForm(HuurderafsluitingType::class, $huurderafsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->huurderafsluitingDao->create($huurderafsluiting);
                $this->addFlash('success', 'Huurderafsluiting is toegevoegd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_huurderafsluitingen_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/odp/admin/huurderafsluitingen/{id}/edit")
     */
    public function edit($id)
    {
        $huurderafsluiting = $this->huurderafsluitingDao->find($id);

        $form = $this->createForm(HuurderafsluitingType::class, $huurderafsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->huurderafsluitingDao->update($huurderafsluiting);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToRoute('odp_huurderafsluitingen_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/odp/admin/huurderafsluitingen/{id}/delete")
     */
    public function delete($id)
    {
        $huurderafsluiting = $this->huurderafsluitingDao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->huurderafsluitingDao->delete($huurderafsluiting);
                    $this->addFlash('success', 'Huurderafsluiting is verwijderd.');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }
            }

            return $this->redirectToRoute('odp_huurderafsluitingen_index');
        }

        return [
            'form' => $form->createView(),
            'huurderafsluiting' => $huurderafsluiting,
        ];
    }
}
