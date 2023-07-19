<?php

namespace DagbestedingBundle\Controller;

use DagbestedingBundle\Entity\Intakeverslag;
use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Verslag;
use DagbestedingBundle\Form\VerslagType;
use DagbestedingBundle\Service\VerslagDao;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intakeverslagen")

 */
class IntakeverslagenController extends AbstractChildController
{
    protected $entityName = 'Intakeverslag';
    protected $entityClass = Intakeverslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addIntakeverslag';
    protected $baseRouteName = 'dagbesteding_intakeverslagen_';

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
