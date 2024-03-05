<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use DagbestedingBundle\Entity\Werkdoel;
use DagbestedingBundle\Form\WerkdoelType;
use DagbestedingBundle\Service\WerkdoelDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/werkdoelen")
 */
class WerkdoelenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'werkdoel';
    protected $entityClass = Werkdoel::class;
    protected $formClass = WerkdoelType::class;
    protected $addMethod = 'addWerkdoel';
    protected $baseRouteName = 'dagbesteding_werkdoelen_';
    protected $disabledActions = ['index', 'view'];

    /**
     * @var WerkdoelDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(WerkdoelDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
