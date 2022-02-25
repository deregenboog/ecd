<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Entity\Klant;
use JMS\DiExtraBundle\Annotation as DI;
use MwBundle\Entity\Document;
use MwBundle\Entity\Vrijwilliger;
use MwBundle\Form\DocumentType;
use MwBundle\Service\DocumentDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
    protected $baseRouteName = 'mw_documenten_';
    protected $allowEmpty = true;
    protected $disabledActions = ['index', 'edit'];

    /**
     * @var DocumentDaoInterface
     *
     * @DI\Inject("MwBundle\Service\DocumentDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("mw.document.entities")
     */
    protected $entities;

    /**
     * @Route("/download/{filename}")
     */
    public function downloadAction($filename)
    {
        $document = $this->dao->findByFilename($filename);

        $downloadHandler = $this->get('vich_uploader.download_handler');

        return $downloadHandler->downloadObject($document, 'file');
    }

    /**
     * @Route("/add")
     * @Template
     */
    public function addAction(Request $request)
    {
        [$parentEntity, $this->parentDao] = $this->getParentConfig($request);
        if($parentEntity instanceof Klant)
        {
            $this->addMethod = null;
        }
        elseif($parentEntity instanceof Vrijwilliger)
        {
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
