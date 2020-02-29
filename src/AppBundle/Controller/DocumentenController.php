<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\Overeenkomst;
use AppBundle\Entity\Toestemmingsformulier;
use AppBundle\Entity\Vog;
use AppBundle\Form\DocumentType;
use AppBundle\Service\DocumentDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
    protected $title = 'Documenten';
    protected $entityName = 'Document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $baseRouteName = 'dagbesteding_documenten_';

    /**
     * @var DocumentDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct()
    {
        $this->dao = $this->get("AppBundle\Service\DocumentDao");
        $this->entities = $this->get("app.document.entities");
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


    public function createEntity($parentEntity = null)
    {
        switch ($this->getRequest()->get('type')) {
            case 'vog':
                $this->addMethod = 'setVog';

                return new Vog();
            case 'overeenkomst':
                $this->addMethod = 'setOvereenkomst';

                return new Overeenkomst();
            case 'toestemming':
                $this->addMethod = 'setToestemmingsformulier';

                return new Toestemmingsformulier();
            default:

                return new Document();
        }
    }
}
