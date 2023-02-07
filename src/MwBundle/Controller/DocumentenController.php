<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Entity\Klant;
use MwBundle\Entity\Document;
use MwBundle\Entity\Vrijwilliger;
use MwBundle\Form\DocumentType;
use MwBundle\Service\DocumentDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
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
    protected $baseRouteName = 'mw_documenten_';
    protected $allowEmpty = true;
    protected $disabledActions = ['index', 'edit'];

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

    /**
     * @Route("/add")
     * @Template
     */
    public function addAction(Request $request)
    {
        [$parentEntity, $this->parentDao] = $this->getParentConfig($request);
        if ($parentEntity instanceof Klant) {
            $this->addMethod = null;
        } elseif ($parentEntity instanceof Vrijwilliger) {
            $this->addMethod = 'addDocument';
        }

        return parent::addAction($request);
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
