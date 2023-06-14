<?php

namespace TwBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Exception\UserException;
use AppBundle\Form\ConfirmationType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use InloopBundle\Security\Permissions;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use TwBundle\Entity\Document;
use TwBundle\Entity\Klant;
use TwBundle\Entity\Huurovereenkomst;
use TwBundle\Entity\Verhuurder;
use TwBundle\Entity\Vrijwilliger;
use TwBundle\Exception\TwException;
use TwBundle\Form\DocumentType;
use TwBundle\Service\DocumentDao;
use TwBundle\Service\DocumentDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Handler\DownloadHandler;

/**
 * @Route("/documenten")
 * @Template
 */
class DocumentenController extends SymfonyController
{
    public $title = 'Documenten';

    /**
     * @var DocumentDao
     */
    private $dao;

    /**
     * @param DocumentDao $dao
     */
    public function __construct(DocumentDao $dao, EntityManagerInterface $entityManager)
    {
        $this->dao = $dao;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/download/{filename}")
     */
    public function download($filename, DownloadHandler $downloadHandler)
    {
        $document = $this->dao->findByFilename($filename);

//        $downloadHandler = $this->get('vich_uploader.download_handler');

        return $downloadHandler->downloadObject($document, 'file');
    }

    /**
     * @Route("/add")
     */
    public function add()
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->findEntity($entityManager);

        $form = $this->getForm(DocumentType::class, new Document());
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $routeBase = $this->resolveRouteBase($entity);
            try {
                $entityManager->persist($entity->addDocument($form->getData()));
                $entityManager->flush();
                $this->addFlash('success', 'Document is toegevoegd.');
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);

                return $this->redirectToRoute($routeBase.'_index');
            }

            return $this->redirectToRoute($routeBase.'_view', ['id' => $entity->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id, $redirect = null)
    {
        $entity = $this->dao->find($id);

        try {
            $this->denyAccessUnlessGranted(
                Permissions::OWNER,
                $entity,
                'Je kunt alleen documenten wijzigen/verwijderen die door jezelf zijn aangemaakt. Een beheerder kan bestanden wel wijzigen.'
            );
        }
        catch(AccessDeniedException $e)
        {
            $this->addFlash("danger",$e->getMessage());
            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }
        }

        $form = $this->getForm(DocumentType::class, $entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Document is bijgewerkt.');
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
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

        try {
            $this->denyAccessUnlessGranted(
                Permissions::OWNER,
                $entity,
                'Je kunt alleen documenten wijzigen/verwijderen die door jezelf zijn aangemaakt. Een beheerder kan bestanden wel wijzigen.'
            );
        }
        catch(AccessDeniedException $e)
        {
            $this->addFlash("danger",$e->getMessage());
            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }
        }
        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $this->dao->delete($entity);
                $this->addFlash('success', 'Document is verwijderd.');
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

    private function findEntity(EntityManager $entityManager)
    {
        switch (true) {
            case $this->getRequest()->query->has('klant'):
                $class = Klant::class;
                $id = $this->getRequest()->query->get('klant');
                break;
            case $this->getRequest()->query->has('verhuurder'):
                $class = Verhuurder::class;
                $id = $this->getRequest()->query->get('verhuurder');
                break;
            case $this->getRequest()->query->has('huurovereenkomst'):
                $class = Huurovereenkomst::class;
                $id = $this->getRequest()->query->get('huurovereenkomst');
                break;
            case $this->getRequest()->query->has('vrijwilliger'):
                $class = Vrijwilliger::class;
                $id = $this->getRequest()->query->get('vrijwilliger');
                break;
            default:
                throw new TwException('Kan geen document aan deze entiteit toevoegen');
        }

        return $entityManager->find($class, $id);
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
            case $entity instanceof Huurovereenkomst:
                $routeBase = 'tw_huurovereenkomsten';
                break;
            case $entity instanceof Vrijwilliger:
                $routeBase = 'tw_vrijwilligers';
                break;
            default:
                throw new TwException('Kan geen document aan deze entiteit toevoegen');
        }

        return $routeBase;
    }
}
