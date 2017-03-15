<?php

namespace OdpBundle\Controller;

use OdpBundle\Entity\Huurder;
use OdpBundle\Entity\Verhuurder;
use OdpBundle\Entity\Huurovereenkomst;
use OdpBundle\Entity\Document;
use OdpBundle\Form\DocumentType;
use OdpBundle\Exception\OdpException;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\SymfonyController;
use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Service\DocumentDaoInterface;

class DocumentenController extends SymfonyController
{
    /**
     * @var DocumentDaoInterface
     *
     * @DI\Inject("odp.dao.document")
     */
    private $documentDao;

    /**
     * @Route("/odp/documenten/download/{filename}")
     */
    public function download($filename)
    {
        $document = $this->documentDao->findByFilename($filename);

        $downloadHandler = $this->get('vich_uploader.download_handler');

        return $downloadHandler->downloadObject($document, 'file');
    }

    /**
     * @Route("/odp/documenten/add")
     */
    public function add()
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->findEntity($entityManager);

        $form = $this->createForm(DocumentType::class, new Document());
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $routeBase = $this->resolveRouteBase($entity);
            try {
                $entityManager->persist($entity->addDocument($form->getData()));
                $entityManager->flush();
                $this->addFlash('success', 'Document is toegevoegd.');
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
            case $this->getRequest()->query->has('huurovereenkomst'):
                $class = Huurovereenkomst::class;
                $id = $this->getRequest()->query->get('huurovereenkomst');
                break;
            default:
                throw new OdpException('Kan geen document aan deze entiteit toevoegen');
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
            case $entity instanceof Huurovereenkomst:
                $routeBase = 'odp_huurovereenkomsten';
                break;
            default:
                throw new OdpException('Kan geen document aan deze entiteit toevoegen');
        }

        return $routeBase;
    }
}
