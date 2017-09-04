<?php

namespace DagbestedingBundle\Controller;

use DagbestedingBundle\Entity\Verslag;
use DagbestedingBundle\Form\VerslagType;
use DagbestedingBundle\Service\VerslagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\AbstractChildController;

/**
 * @Route("/verslagen")
 */
class VerslagenController extends AbstractChildController
{
    protected $title = 'Verslagen';
    protected $entityName = 'Verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addVerslag';
    protected $baseRouteName = 'dagbesteding_verslagen_';

    /**
     * @var VerslagDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.verslag")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("dagbesteding.verslag.entities")
     */
    protected $entities;
}
