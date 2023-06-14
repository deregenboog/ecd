<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Rapportage;
use DagbestedingBundle\Form\RapportageType;
use DagbestedingBundle\Service\RapportageDao;
use DagbestedingBundle\Service\RapportageDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportages")
 */
class RapportagesController extends AbstractChildController
{
    protected $entityName = 'Rapportage';
    protected $entityClass = Rapportage::class;
    protected $formClass = RapportageType::class;
    protected $addMethod = 'addRapportage';
    protected $baseRouteName = 'dagbesteding_rapportages_';

    /**
     * @var RapportageDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param RapportageDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(RapportageDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }


}
