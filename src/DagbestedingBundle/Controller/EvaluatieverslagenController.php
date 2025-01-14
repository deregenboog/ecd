<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use DagbestedingBundle\Entity\Evaluatieverslag;
use DagbestedingBundle\Form\VerslagType;
use DagbestedingBundle\Service\VerslagDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evaluatieverslagen")
 */
class EvaluatieverslagenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'Evaluatie';
    protected $entityClass = Evaluatieverslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addEvaluatieverslag';
    protected $baseRouteName = 'dagbesteding_evaluatieverslagen_';

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
