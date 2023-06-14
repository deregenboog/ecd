<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use JMS\DiExtraBundle\Annotation as DI;
use UhkBundle\Entity\Document;
use UhkBundle\Form\DocumentType;
use UhkBundle\Service\DocumentDao;
use UhkBundle\Service\DocumentDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
    protected $entityName = 'document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $deleteMethod = 'removeDocument';
    protected $baseRouteName = 'uhk_documenten_';

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
