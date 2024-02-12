<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use ScipBundle\Entity\Deelname;
use ScipBundle\Entity\Deelnemer;
use ScipBundle\Entity\Project;
use ScipBundle\Form\DeelnameType;
use ScipBundle\Service\DeelnameDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

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
    protected $baseRouteName = 'scip_deelnames_';
    protected $disabledActions = ['index', 'view'];

    /**
     * @var DeelnameDaoInterface
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
     * @Route("/")
     */
    public function downloadExportAction()
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
        $deelnemer = $parentEntity instanceof Deelnemer ? $parentEntity : null;
        $project = $parentEntity instanceof Project ? $parentEntity : null;

        return new Deelname($deelnemer, $project);
    }
}
