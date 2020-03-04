<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use ScipBundle\Entity\Document;
use ScipBundle\Form\DocumentType;
use ScipBundle\Security\Permissions;
use ScipBundle\Service\DocumentDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
    protected $title = 'Documenten';
    protected $entityName = 'document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $baseRouteName = 'scip_documenten_';
    protected $disabledActions = ['index', 'view', 'deleted'];

    /**
     * @var DocumentDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("ScipBundle\Service\DocumentDao");
        $this->entities = $container->get("scip.document.entities");
    
        return $previous;
    }

    /**
     * @Route("/download/{filename}")
     */
    public function downloadAction(Request $request, $filename)
    {
        $document = $this->dao->findByFilename($filename);

        $this->denyAccessUnlessGranted(Permissions::ACCESS, $document);

        $downloadHandler = $this->get('vich_uploader.download_handler');

        try {
            return $downloadHandler->downloadObject($document, 'file');
        } catch (\ErrorException $e) {
            $url = $request->get('redirect')
                ?? $request->server->get('HTTP_REFERER')
                ?? $this->get('router')->generate('scip_index');

            $this->addFlash('danger', ucfirst($this->entityName).' niet gevonden.');

            return $this->redirect($url);
        }
    }
}
