<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Training;
use InloopBundle\Entity\VwTraining;
use InloopBundle\Form\LocatieType;
use InloopBundle\Form\TrainingType;
use InloopBundle\Service\LocatieDaoInterface;
use InloopBundle\Service\TrainingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/vwtrainingen")
 * @Template
 */
class TrainingenController extends AbstractController
{
    protected $entityName = 'Training';
    protected $entityClass = Training::class;
    protected $formClass = TrainingType::class;
    protected $baseRouteName = 'inloop_trainingen_';

    /**
     * @var TrainingDaoInterface
     */
    protected $dao;

    public function __construct(TrainingDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
