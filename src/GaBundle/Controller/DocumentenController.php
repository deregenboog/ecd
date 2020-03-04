<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use GaBundle\Entity\Document;
use GaBundle\Form\DocumentType;
use GaBundle\Service\DocumentDaoInterface;
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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("GaBundle\Service\DocumentDao");
        $this->entities = $container->get("ga.document.entities");
    
        return $previous;
    }

    /**
     * @Route("/download/{filename}")
     */
    public function downloadAction($filename)
    {
        $document = $this->dao->findByFilename($filename);

        $downloadHandler = $this->get('vich_uploader.download_handler');

        return $downloadHandler->downloadObject($document, 'file');
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
