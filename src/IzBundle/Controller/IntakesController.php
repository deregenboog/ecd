<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Intake;
use IzBundle\Form\IntakeType;
use IzBundle\Service\IntakeDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intakes")
 */
class IntakesController extends AbstractChildController
{
    protected $entityName = 'intake';
    protected $entityClass = Intake::class;
    protected $formClass = IntakeType::class;
    protected $addMethod = 'setIntake';
    protected $baseRouteName = 'iz_intakes_';
    protected $disabledActions = ['index'];

    /**
     * @var IntakeDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var AbstractExport
     */
    protected $export;

    public function __construct(IntakeDaoInterface $dao, \ArrayObject $entities, AbstractExport $export)
    {
        $this->dao = $dao;
        $this->entities = $entities;
        $this->export = $export;
    }
}
