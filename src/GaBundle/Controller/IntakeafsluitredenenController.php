<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Entity\IntakeAfsluitreden;
use GaBundle\Form\IntakeAfsluitredenType;
use Symfony\Component\Routing\Annotation\Route;
use GaBundle\Service\IntakeAfsluitredenDaoInterface;

/**
 * @Route("/intakeafsluitredenen")
 * @todo Wordt dit nog gebruikt?
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
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("GaBundle\Service\IntakeAfsluitredenDao");
    
        return $previous;
    }
}
