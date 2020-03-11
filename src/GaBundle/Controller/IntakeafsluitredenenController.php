<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Entity\IntakeAfsluitreden;
use GaBundle\Form\IntakeAfsluitredenType;
use GaBundle\Service\IntakeAfsluitredenDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intakeafsluitredenen")
 *
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

    public function setContainer(?\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->dao = $container->get("GaBundle\Service\IntakeAfsluitredenDao");
    }
}
