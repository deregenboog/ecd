<?php

namespace TwBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Exception\UserException;
use AppBundle\Form\ConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\Huuraanbod;
use TwBundle\Entity\Huurovereenkomst;
use TwBundle\Entity\Huurverzoek;
use TwBundle\Entity\Klant;
use TwBundle\Entity\Verhuurder;
use TwBundle\Entity\Verslag;
use TwBundle\Exception\TwException;
use TwBundle\Form\VerslagType;
use TwBundle\Service\VerslagDaoInterface;

/**
 * @Route("/verslagen")
 *
 * @Template
 */
class VerslagenController extends SymfonyController
{
    public $title = 'Verslagen';

    /**
     * @var VerslagDaoInterface
     */
    private $dao;

    public function __construct(VerslagDaoInterface $dao, EntityManagerInterface $entityManager)
    {
        $this->dao = $dao;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/add")
     */
    public function add()
    {
        $entityManager = $this->getEntityManager();
        $e = $this->findEntity($entityManager);
        $entity = $e['entity'];
        $type = $e['type'];

        $form = $this->getForm(VerslagType::class, new Verslag());
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $routeBase = $this->resolveRouteBase($entity);
            try {
                $verslagData = $form->getData();
                $entityManager->persist($entity->addVerslag($verslagData));
                $entityManager->flush();
                $this->addFlash('success', 'Verslag is toegevoegd.');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);

                return $this->redirectToRoute($routeBase.'_index');
            }

            return $this->redirectToRoute($routeBase.'_view', ['id' => $entity->getId()]);
        }

        return [
            'form' => $form->createView(),
            'type' => $type,
        ];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id, $redirect = null)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm(VerslagType::class, $entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Verslag is bijgewerkt.');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('tw_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $this->dao->delete($entity);
                $this->addFlash('success', 'Verslag is verwijderd.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('tw_index');
        }

        return [
            'form' => $form->createView(),
            'entity' => $entity,
        ];
    }

    private function findEntity(EntityManagerInterface $entityManager)
    {
        switch (true) {
            case $this->getRequest()->query->has('klant'):
                $class = Klant::class;
                $id = $this->getRequest()->query->get('klant');
                $type = 'Klant';
                break;
            case $this->getRequest()->query->has('verhuurder'):
                $class = Verhuurder::class;
                $id = $this->getRequest()->query->get('verhuurder');
                $type = 'Verhuurder';
                break;
            case $this->getRequest()->query->has('huurverzoek'):
                $class = Huurverzoek::class;
                $id = $this->getRequest()->query->get('huurverzoek');
                $type = 'Huurverzoek';
                break;
            case $this->getRequest()->query->has('huuraanbod'):
                $class = Huuraanbod::class;
                $id = $this->getRequest()->query->get('huuraanbod');
                $type = 'Huuraanbod';
                break;
            case $this->getRequest()->query->has('huurovereenkomst'):
                $class = Huurovereenkomst::class;
                $id = $this->getRequest()->query->get('huurovereenkomst');
                $type = 'Koppeling';
                break;
            default:
                throw new TwException('Kan geen verslag aan deze entiteit toevoegen');
        }

        return [
            'entity' => $entityManager->find($class, $id),
            'type' => $type,
            ];
    }

    private function resolveRouteBase($entity)
    {
        switch (true) {
            case $entity instanceof Klant:
                $routeBase = 'tw_klanten';
                break;
            case $entity instanceof Verhuurder:
                $routeBase = 'tw_verhuurders';
                break;
            case $entity instanceof Huurverzoek:
                $routeBase = 'tw_huurverzoeken';
                break;
            case $entity instanceof Huuraanbod:
                $routeBase = 'tw_huuraanbiedingen';
                break;
            case $entity instanceof Huurovereenkomst:
                $routeBase = 'tw_huurovereenkomsten';
                break;
            default:
                throw new TwException('Kan geen verslag aan deze entiteit toevoegen');
        }

        return $routeBase;
    }
}
