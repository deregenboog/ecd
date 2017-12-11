<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Export\GenericExport;
use DagbestedingBundle\Entity\Dagdeel;
use DagbestedingBundle\Form\DagdeelFilterType;

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

    /**
     * @var DagdeelDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.dagdeel")
     */
    protected $dao;

    /**
     * @var GenericExport
     *
     * @DI\Inject("dagbesteding.export.dagdelen")
     */
    protected $export;
}
