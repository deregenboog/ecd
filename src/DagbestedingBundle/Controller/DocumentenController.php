<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use DagbestedingBundle\Entity\Document;
use DagbestedingBundle\Form\DocumentType;
use DagbestedingBundle\Service\DocumentDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'Document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $baseRouteName = 'dagbesteding_documenten_';

    /**
     * @var DocumentDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(DocumentDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
