<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\GenericExport;
use DagbestedingBundle\Entity\Dagdeel;
use DagbestedingBundle\Form\DagdeelFilterType;
use DagbestedingBundle\Service\DagdeelDao;
use DagbestedingBundle\Service\DagdeelDaoInterface;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dagdelen")
 */
class DagdelenController extends AbstractController
{
    protected $title = 'Dagdelen';
    protected $entityName = 'dagdeel';
    protected $entityClass = Dagdeel::class;
    protected $filterFormClass = DagdeelFilterType::class;
    protected $baseRouteName = 'dagbesteding_dagdelen_';
    protected $disabledActions = ['view', 'add', 'edit', 'delete'];

    /**
     * @var DagdeelDao
     */
    protected $dao;

    /**
     * @var GenericExport
     */
    protected $export;

    /**
     * @param DagdeelDao $dao
     * @param GenericExport $export
     */
    public function __construct(DagdeelDao $dao, GenericExport $export)
    {
        $this->dao = $dao;
        $this->export = $export;
    }


}
