<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use IzBundle\Entity\Lidmaatschap;
use IzBundle\Form\LidmaatschapType;
use IzBundle\Service\LidmaatschapDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lidmaatschappen")
 */
class LidmaatschappenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'lidmaatschap';
    protected $entityClass = Lidmaatschap::class;
    protected $formClass = LidmaatschapType::class;
    protected $addMethod = 'addLidmaatschap';
    protected $baseRouteName = 'iz_lidmaatschappen_';
    protected $disabledActions = ['index', 'view', 'edit'];

    /**
     * @var LidmaatschapDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(LidmaatschapDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
