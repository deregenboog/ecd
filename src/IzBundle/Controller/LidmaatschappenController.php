<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use IzBundle\Entity\Lidmaatschap;
use IzBundle\Form\LidmaatschapType;
use IzBundle\Service\LidmaatschapDao;
use IzBundle\Service\LidmaatschapDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lidmaatschappen")
 */
class LidmaatschappenController extends AbstractChildController
{
    protected $title = 'Lidmaatschappen';
    protected $entityName = 'lidmaatschap';
    protected $entityClass = Lidmaatschap::class;
    protected $formClass = LidmaatschapType::class;
    protected $addMethod = 'addLidmaatschap';
    protected $baseRouteName = 'iz_lidmaatschappen_';
    protected $disabledActions = ['index', 'view', 'edit'];

    /**
     * @var LidmaatschapDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param LidmaatschapDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(LidmaatschapDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }


}
