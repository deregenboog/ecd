<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\Training;
use TwBundle\Form\TrainingType;
use TwBundle\Service\TrainingDaoInterface;

/**
 * @Route("/trainingen")
 *
 * @Template
 */
class TrainingenController extends AbstractController
{
    protected $entityName = 'Training';
    protected $entityClass = Training::class;
    protected $formClass = TrainingType::class;
    protected $baseRouteName = 'tw_trainingen_';

    /**
     * @var TrainingDaoInterface
     */
    protected $dao;

    public function __construct(TrainingDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
