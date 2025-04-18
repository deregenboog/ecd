<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekraineBundle\Entity\Training;
use OekraineBundle\Form\TrainingType;
use OekraineBundle\Service\TrainingDaoInterface;
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
    protected $baseRouteName = 'oekraine_trainingen_';

    /**
     * @var TrainingDaoInterface
     */
    protected $dao;

    public function __construct(TrainingDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
