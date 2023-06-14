<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Document;
use DagbestedingBundle\Form\DocumentType;
use DagbestedingBundle\Service\DocumentDao;
use DagbestedingBundle\Service\DocumentDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
    protected $entityName = 'Document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $baseRouteName = 'dagbesteding_documenten_';

    /**
     * @var DocumentDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param DocumentDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(DocumentDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }


}
