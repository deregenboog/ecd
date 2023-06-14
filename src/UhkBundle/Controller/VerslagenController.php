<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use JMS\DiExtraBundle\Annotation as DI;
use UhkBundle\Entity\Verslag;
use UhkBundle\Form\VerslagType;
use UhkBundle\Service\VerslagDao;
use UhkBundle\Service\VerslagDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/verslagen")
 */
class VerslagenController extends AbstractChildController
{
    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addVerslag';
    protected $baseRouteName = 'uhk_verslagen_';
    protected $disabledActions = ['index', 'view'];

    /**
     * @var VerslagDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param VerslagDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(VerslagDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

}
