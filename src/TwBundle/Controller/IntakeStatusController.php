<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\IntakeStatus;
use TwBundle\Form\IntakeStatusType;
use TwBundle\Service\IntakeStatusDaoInterface;

/**
 * @Route("/intakestatus")
 */
class IntakeStatusController extends AbstractController
{
    protected $title = 'Intake statussen';
    protected $entityName = 'Intake status';
    protected $entityClass = IntakeStatus::class;
    protected $formClass = IntakeStatusType::class;
    protected $baseRouteName = 'tw_intakestatus_';

    /**
     * @var IntakeStatusDaoInterface
     */
    protected $dao;

    public function __construct(IntakeStatusDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
