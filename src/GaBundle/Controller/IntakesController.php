<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use GaBundle\Entity\Intake;
use GaBundle\Form\IntakeFilterType;
use GaBundle\Form\IntakeType;
use GaBundle\Service\IntakeDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intakes")
 * @Template
 */
class IntakesController extends AbstractChildController
{
    protected $title = 'Intakes';
    protected $entityName = 'intake';
    protected $entityClass = Intake::class;
    protected $formClass = IntakeType::class;
    protected $filterFormClass = IntakeFilterType::class;
    protected $allowEmpty = true;
    protected $baseRouteName = 'ga_intakes_';

    /**
     * @var IntakeDaoInterface
     *
     * @DI\Inject("GaBundle\Service\IntakeDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("ga.intake.entities")
     */
    protected $entities;

    protected function createEntity($parentEntity = null)
    {
        return new $this->entityClass($parentEntity);
    }
}
