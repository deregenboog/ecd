<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\GenericExport;
use DagbestedingBundle\Entity\Dagdeel;
use DagbestedingBundle\Form\DagdeelFilterType;
use DagbestedingBundle\Service\DagdeelDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dagdelen")
 */
class DagdelenController extends AbstractController
{
    protected $entityName = 'dagdeel';
    protected $entityClass = Dagdeel::class;
    protected $filterFormClass = DagdeelFilterType::class;
    protected $baseRouteName = 'dagbesteding_dagdelen_';
    protected $disabledActions = ['view', 'add', 'edit', 'delete'];

    /**
     * @var DagdeelDaoInterface
     */
    protected $dao;

    /**
     * @var GenericExport
     */
    protected $export;

    public function __construct(DagdeelDaoInterface $dao, GenericExport $export)
    {
        $this->dao = $dao;
        $this->export = $export;
    }
}
