<?php

namespace DagbestedingBundle\Controller;

use DagbestedingBundle\Entity\Document;
use DagbestedingBundle\Form\DocumentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\DiExtraBundle\Annotation as DI;
use DagbestedingBundle\Service\DocumentDaoInterface;
use AppBundle\Controller\AbstractChildController;

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
}
