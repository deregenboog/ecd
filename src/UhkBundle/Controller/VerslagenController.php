<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use Symfony\Component\Routing\Annotation\Route;
use UhkBundle\Entity\Verslag;
use UhkBundle\Form\VerslagType;
use UhkBundle\Service\VerslagDaoInterface;

/**
 * @Route("/verslagen")
 */
class VerslagenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addVerslag';
    protected $baseRouteName = 'uhk_verslagen_';
    protected $disabledActions = ['index', 'view'];

    /**
     * @var VerslagDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(VerslagDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
