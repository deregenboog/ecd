<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Training;
use IzBundle\Form\TrainingType;
use IzBundle\Service\TrainingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/vwtrainingen")
 *
 * @Template
 */
class TrainingenController extends AbstractController
{
    protected $entityName = 'Training';
    protected $entityClass = Training::class;
    protected $formClass = TrainingType::class;
    protected $baseRouteName = 'iz_trainingen_';

    /**
     * @var TrainingDaoInterface
     */
    protected $dao;

    public function __construct(TrainingDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
