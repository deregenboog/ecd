<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use JMS\DiExtraBundle\Annotation as DI;
use UhkBundle\Entity\Document;
use UhkBundle\Form\DocumentType;
use UhkBundle\Service\DocumentDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

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
    protected $deleteMethod = 'removeDocument';
    protected $baseRouteName = 'uhk_documenten_';

    /**
     * @var DocumentDaoInterface
     *
     * @DI\Inject("UhkBundle\Service\DocumentDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("uhk.document.entities")
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
