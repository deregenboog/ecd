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
use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Service\VerslagDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ConfirmationType;

class VerslagenController extends SymfonyController
{
    /**
     * @var VerslagDaoInterface
     *
     * @DI\Inject("odp.dao.verslag")
     */
    private $dao;

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

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/odp/verslagen/{id}/edit")
     */
    public function editAction(Request $request, $id, $redirect = null)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(VerslagType::class, $entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Verslag is bijgewerkt.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('odp_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/odp/verslagen/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $this->dao->delete($entity);
                $this->addFlash('success', 'Verslag is verwijderd.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('odp_index');
        }

        return [
            'form' => $form->createView(),
            'entity' => $entity,
        ];
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
