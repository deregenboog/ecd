<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use InloopBundle\Entity\Intake;
use InloopBundle\Form\IntakeFilterType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/intakes")
 */
class IntakesController extends AbstractController
{
    protected $title = 'Intakes';
    protected $entityName = 'intake';
    protected $entityClass = Intake::class;
    protected $formClass = IntakeType::class;
    protected $filterFormClass = IntakeFilterType::class;
    protected $baseRouteName = 'inloop_intakes_';

    /**
     * @var IntakeDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\IntakeDao")
     */
    protected $dao;

//     /**
//      * @var ExportInterface
//      *
//      * @DI\Inject("inloop.export.intakes")
//      */
//     protected $export;
}
