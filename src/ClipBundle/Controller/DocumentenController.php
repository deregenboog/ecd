<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use ClipBundle\Entity\Document;
use ClipBundle\Form\DocumentType;
use ClipBundle\Service\DocumentDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
    protected $title = 'Documenten';
    protected $entityName = 'Document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $baseRouteName = 'dagbesteding_documenten_';

    /**
     * @var DocumentDaoInterface
     *
     * @DI\Inject("ClipBundle\Service\DocumentDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("clip.document.entities")
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
}
