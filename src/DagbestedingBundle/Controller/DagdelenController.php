<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\GenericExport;
use DagbestedingBundle\Entity\Dagdeel;
use DagbestedingBundle\Form\DagdeelFilterType;
use DagbestedingBundle\Service\DagdeelDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
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
     * @var DagdeelDaoInterface
     *
     * @DI\Inject("DagbestedingBundle\Service\DagdeelDao")
     */
    protected $dao;

    /**
     * @var GenericExport
     *
     * @DI\Inject("dagbesteding.export.dagdelen")
     */
    protected $export;
}
