<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\DisableIndexActionTrait;
use DagbestedingBundle\Entity\Intakeverslag;
use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Verslag;
use DagbestedingBundle\Form\VerslagType;
use DagbestedingBundle\Service\VerslagDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intakeverslagen")
 */
class IntakeverslagenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'Intakeverslag';
    protected $entityClass = Intakeverslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addIntakeverslag';
    protected $baseRouteName = 'dagbesteding_intakeverslagen_';

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
