<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use ClipBundle\Entity\Document;
use ClipBundle\Form\DocumentType;
use ClipBundle\Service\DocumentDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

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
    protected $allowEmpty = true;
    protected $baseRouteName = 'clip_documenten_';

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
