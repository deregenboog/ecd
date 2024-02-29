<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Rapportage;
use DagbestedingBundle\Form\RapportageType;
use DagbestedingBundle\Service\RapportageDaoInterface;
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
     * @var RapportageDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(RapportageDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
