<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Form\DocumentType;
use OekraineBundle\Entity\Document;
use OekraineBundle\Service\DocumentDao;
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
    protected $formOptions = [
        'enabled_fields' => ['naam'],
        'data_class' => Document::class,
    ];
    protected $addMethod = 'addDocument';
    protected $deleteMethod = 'removeDocument';
    protected $baseRouteName = 'oekraine_documenten_';

    /**
     * @var DocumentDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(DocumentDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
