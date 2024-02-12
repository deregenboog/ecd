<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\Overeenkomst;
use AppBundle\Entity\Toestemmingsformulier;
use AppBundle\Entity\Vog;
use AppBundle\Form\DocumentType;
use AppBundle\Service\DocumentDao;
use AppBundle\Service\DocumentDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
    protected $entityName = 'Document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $baseRouteName = 'app_documenten_';

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
