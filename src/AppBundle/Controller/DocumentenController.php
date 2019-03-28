<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\Overeenkomst;
use AppBundle\Entity\Vog;
use AppBundle\Form\DocumentType;
use AppBundle\Service\DocumentDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     *
     * @DI\Inject("AppBundle\Service\DocumentDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("app.document.entities")
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

    public function createEntity($parentEntity = null)
    {
        switch ($this->getRequest()->get('type')) {
            case 'vog':
                $this->addMethod = 'setVog';

                return new Vog();
            case 'overeenkomst':
                $this->addMethod = 'setOvereenkomst';

                return new Overeenkomst();
            default:

                return new Document();
        }
    }
}
