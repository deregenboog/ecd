<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Verslag;
use DagbestedingBundle\Form\VerslagType;
use DagbestedingBundle\Service\VerslagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

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
     * @DI\Inject("DagbestedingBundle\Service\VerslagDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("dagbesteding.verslag.entities")
     */
    protected $entities;
}
