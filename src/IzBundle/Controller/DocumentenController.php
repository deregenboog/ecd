<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use IzBundle\Entity\Document;
use IzBundle\Form\DocumentType;
use IzBundle\Service\DocumentDao;
use IzBundle\Service\DocumentDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/documenten")
 * @Template
 */
class DocumentenController extends AbstractChildController
{
    protected $entityName = 'document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $baseRouteName = 'iz_documenten_';

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
