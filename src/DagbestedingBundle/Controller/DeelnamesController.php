<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Service\DeelnameDao;
use JMS\DiExtraBundle\Annotation as DI;
use DagbestedingBundle\Entity\Deelname;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Form\DeelnameType;
use DagbestedingBundle\Service\DeelnameDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

/**
 * @Route("/deelnames")
 * @Template
 */
class DeelnamesController extends AbstractChildController
{
    protected $entityName = 'deelname';
    protected $entityClass = Deelname::class;
    protected $formClass = DeelnameType::class;
    protected $addMethod = 'addDeelname';
    protected $baseRouteName = 'dagbesteding_deelnames_';
    protected $disabledActions = ['index', 'view'];

    /**
     * @var DeelnameDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param DeelnameDao $dao
     * @param \ArrayObject $entities
     * @param ExportInterface $export
     */
    public function __construct(DeelnameDao $dao, \ArrayObject $entities, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->entities = $entities;
        $this->export = $export;
    }


    /**
     * @Route("/")
     */
    public function downloadAction(DownloadHandler $downloadHandler, $filename = null)
    {
        ini_set('memory_limit', '512M');

        $filename = $this->getDownloadFilename();

        if ($this->isGranted('ROLE_SCIP_BEHEER')) {
            $collection = $this->dao->findAll();
        } else {
            $collection = $this->dao->findByMedewerker($this->getMedewerker());
        }

        return $this->export->create($collection)->getResponse($filename);
    }

    protected function createEntity($parentEntity = null)
    {
        $traject = $parentEntity instanceof Traject ? $parentEntity : null;
        $project = $parentEntity instanceof Project ? $parentEntity : null;

        return new Deelname($traject, $project);
    }
}
