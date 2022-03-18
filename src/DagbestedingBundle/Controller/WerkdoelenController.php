<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use JMS\DiExtraBundle\Annotation as DI;
use DagbestedingBundle\Entity\Werkdoel;
use DagbestedingBundle\Form\WerkdoelType;
use DagbestedingBundle\Service\WerkdoelDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/werkdoelen")
 */
class WerkdoelenController extends AbstractChildController
{
    protected $title = 'Werkdoelen';
    protected $entityName = 'werkdoel';
    protected $entityClass = Werkdoel::class;
    protected $formClass = WerkdoelType::class;
    protected $addMethod = 'addWerkdoel';
    protected $baseRouteName = 'dagbesteding_werkdoelen_';
    protected $disabledActions = ['index', 'view'];

    /**
     * @var WerkdoelDaoInterface
     *
     * @DI\Inject("DagbestedingBundle\Service\WerkdoelDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("dagbesteding.werkdoel.entities")
     */
    protected $entities;
}
