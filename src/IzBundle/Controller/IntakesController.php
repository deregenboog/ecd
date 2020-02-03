<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Intake;
use IzBundle\Form\IntakeType;
use IzBundle\Service\IntakeDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intakes")
 */
class IntakesController extends AbstractChildController
{
    protected $title = 'Intakes';
    protected $entityName = 'intake';
    protected $entityClass = Intake::class;
    protected $formClass = IntakeType::class;
    protected $addMethod = 'setIntake';
    protected $baseRouteName = 'iz_intakes_';
    protected $disabledActions = ['index'];

    /**
     * @var IntakeDaoInterface
     *
     * @DI\Inject("IzBundle\Service\IntakeDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("iz.intake.entities")
     */
    protected $entities;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.klanten")
     */
    protected $export;
}
