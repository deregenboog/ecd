<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use Symfony\Component\Routing\Annotation\Route;
use VillaBundle\Entity\Document;
use VillaBundle\Form\DocumentType;
use VillaBundle\Service\DocumentDaoInterface;

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
    protected $baseRouteName = 'villa_documenten_';

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
