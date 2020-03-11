<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use Symfony\Component\Routing\Annotation\Route;
use UhkBundle\Entity\Document;
use UhkBundle\Form\DocumentType;
use UhkBundle\Service\DocumentDaoInterface;

/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
    protected $title = 'Documenten';
    protected $entityName = 'document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $deleteMethod = 'removeDocument';
    protected $baseRouteName = 'uhk_documenten_';

    /**
     * @var DocumentDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(?\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->dao = $container->get("UhkBundle\Service\DocumentDao");
        $this->entities = $container->get('uhk.document.entities');
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
}
