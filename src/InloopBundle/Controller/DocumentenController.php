<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use InloopBundle\Entity\Document;
use InloopBundle\Form\DocumentType;
use InloopBundle\Service\DocumentDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
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
    protected $baseRouteName = 'inloop_documenten_';

    /**
     * @var DocumentDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\DocumentDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("inloop.document.entities")
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
