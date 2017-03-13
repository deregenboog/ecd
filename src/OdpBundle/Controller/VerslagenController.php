<?php

namespace OdpBundle\Controller;

use OdpBundle\Entity\Huuraanbod;
use OdpBundle\Entity\Huurovereenkomst;
use OdpBundle\Entity\Huurder;
use OdpBundle\Entity\Verslag;
use OdpBundle\Form\VerslagType;
use OdpBundle\Entity\Verhuurder;
use OdpBundle\Exception\OdpException;
use OdpBundle\Entity\Huurverzoek;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\SymfonyController;

class VerslagenController extends SymfonyController
{
    /**
     * @Route("/odp/verslagen/add")
     */
    public function add()
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->findEntity($entityManager);

        $form = $this->createForm(VerslagType::class, new Verslag());
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $routeBase = $this->resolveRouteBase($entity);
            try {
                $entityManager->persist($entity->addVerslag($form->getData()));
                $entityManager->flush();
                $this->addFlash('success', 'Verslag is toegevoegd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');

                return $this->redirectToRoute($routeBase.'_index');
            }

            return $this->redirectToRoute($routeBase.'_view', ['id' => $entity->getId()]);
        }

        $this->set('form', $form->createView());
    }

    private function findEntity(EntityManager $entityManager)
    {
        switch (true) {
            case $this->getRequest()->query->has('huurder'):
                $class = Huurder::class;
                $id = $this->getRequest()->query->get('huurder');
                break;
            case $this->getRequest()->query->has('verhuurder'):
                $class = Verhuurder::class;
                $id = $this->getRequest()->query->get('verhuurder');
                break;
            case $this->getRequest()->query->has('huurverzoek'):
                $class = Huurverzoek::class;
                $id = $this->getRequest()->query->get('huurverzoek');
                break;
            case $this->getRequest()->query->has('huuraanbod'):
                $class = Huuraanbod::class;
                $id = $this->getRequest()->query->get('huuraanbod');
                break;
            case $this->getRequest()->query->has('huurovereenkomst'):
                $class = Huurovereenkomst::class;
                $id = $this->getRequest()->query->get('huurovereenkomst');
                break;
            default:
                throw new OdpException('Kan geen verslag aan deze entiteit toevoegen');
        }

        return $entityManager->find($class, $id);
    }

    private function resolveRouteBase($entity)
    {
        switch (true) {
            case $entity instanceof Huurder:
                $routeBase = 'odp_huurders';
                break;
            case $entity instanceof Verhuurder:
                $routeBase = 'odp_verhuurders';
                break;
            case $entity instanceof Huurverzoek:
                $routeBase = 'odp_huurverzoeken';
                break;
            case $entity instanceof Huuraanbod:
                $routeBase = 'odp_huuraanbiedingen';
                break;
            case $entity instanceof Huurovereenkomst:
                $routeBase = 'odp_huurovereenkomsten';
                break;
            default:
                throw new OdpException('Kan geen verslag aan deze entiteit toevoegen');
        }

        return $routeBase;
    }
}
