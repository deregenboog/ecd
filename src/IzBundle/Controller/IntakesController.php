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
    protected $title = 'Intakes';
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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("IzBundle\Service\IntakeDao");
        $this->entities = $container->get("iz.intake.entities");
        $this->export = $container->get("iz.export.klanten");
    
        return $previous;
    }
}
