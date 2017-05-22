<?php

namespace DagbestedingBundle\Controller;

use DagbestedingBundle\Entity\Document;
use DagbestedingBundle\Form\DocumentType;
use DagbestedingBundle\Exception\DagbestedingException;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\DiExtraBundle\Annotation as DI;
use DagbestedingBundle\Service\DocumentDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ConfirmationType;
use AppBundle\Controller\AbstractController;

/**
 * @Route("/dagbesteding/documenten")
 */
class DocumentenController extends AbstractController
{
    protected $title = 'Documenten';
    protected $entityName = 'Document';

    /**
     * @var DocumentDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.document")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("dagbesteding.document.entities")
     */
    protected $entities;

    /**
     * @Route("/download/{filename}")
     */
    public function downloadAction($filename)
    {
        $document = $this->dao->findByFilename($filename);

        $downloadHandler = $this->get('vich_uploader.download_handler');

        return $downloadHandler->downloadObject($document, 'file');
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->findEntity($request, $entityManager);

        $form = $this->createForm(DocumentType::class, new Document());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($entity->addDocument($form->getData()));
                $entityManager->flush();
                $this->addFlash('success', $this->entityName.' is toegevoegd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('dagbesteding_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(DocumentType::class, $entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', $this->entityName.' is bijgewerkt.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('dagbesteding_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $this->dao->delete($entity);
                $this->addFlash('success', $this->entityName.' is verwijderd.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('dagbesteding_index');
        }

        return [
            'form' => $form->createView(),
            'entity' => $entity,
        ];
    }

    private function findEntity(Request $request, EntityManager $entityManager)
    {
        foreach ($this->entities as $entity) {
            $key = $entity['key'];
            if ($request->query->has($key)) {
                $class = $entity['class'];
                $id = $request->query->get($key);

                return $entityManager->find($class, $id);
            }
        }

        throw new DagbestedingException('Kan geen document aan deze entiteit toevoegen');
    }
}
