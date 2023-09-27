<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use TwBundle\Entity\IntakeStatus;
use TwBundle\Form\IntakeStatusType;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Service\IntakeStatusDao;

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
     * @var IntakeStatusDao
     */
    protected $dao;

    /**
     * @param IntakeStatusDao $dao
     */
    public function __construct(IntakeStatusDao $dao)
    {
        $this->dao = $dao;
    }


}
