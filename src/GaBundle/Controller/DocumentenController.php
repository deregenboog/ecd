<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use GaBundle\Entity\Document;
use GaBundle\Form\DocumentType;
use GaBundle\Service\DocumentDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $baseRouteName = 'ga_documenten_';
    protected $allowEmpty = true;
    protected $disabledActions = ['index', 'edit'];

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

    protected function createEntity($parentEntity = null)
    {
        return new Document($parentEntity, $this->getMedewerker());
    }

    protected function persistEntity($entity, $parentEntity)
    {
        $this->dao->create($entity);
    }
}
