<?php

namespace DagbestedingBundle\Controller;

use DagbestedingBundle\Entity\Evaluatieverslag;
use DagbestedingBundle\Entity\Intakeverslag;
use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Verslag;
use DagbestedingBundle\Form\VerslagType;
use DagbestedingBundle\Service\VerslagDao;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evaluatieverslagen")

 */
class EvaluatieverslagenController extends AbstractChildController
{
    protected $entityName = 'Evaluatie';
    protected $entityClass = Evaluatieverslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addEvaluatieverslag';
    protected $baseRouteName = 'dagbesteding_evaluatieverslagen_';

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
