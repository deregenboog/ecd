<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Entity\IntakeAfsluitreden;
use GaBundle\Form\IntakeAfsluitredenType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/intakeafsluitredenen")
 */
class IntakeafsluitredenenController extends AbstractController
{
    protected $title = 'Afsluitredenen intake';
    protected $entityName = 'afsluitreden intake';
    protected $entityClass = IntakeAfsluitreden::class;
    protected $formClass = IntakeAfsluitredenType::class;
    protected $baseRouteName = 'ga_intakeafsluitredenen_';

    /**
     * @var IntakeAfsluitredenDaoInterface
     *
     * @DI\Inject("GaBundle\Service\IntakeAfsluitredenDao")
     */
    protected $dao;
}
