<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use ScipBundle\Entity\Document;
use ScipBundle\Form\DocumentType;
use ScipBundle\Security\Permissions;
use ScipBundle\Service\DocumentDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
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

    /**
     * @Route("/download/{filename}")
     */
    public function downloadDocAction(Request $request, DownloadHandler $downloadHandler, $filename)
    {
        $document = $this->dao->findByFilename($filename);

        $this->denyAccessUnlessGranted(Permissions::ACCESS, $document);

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
