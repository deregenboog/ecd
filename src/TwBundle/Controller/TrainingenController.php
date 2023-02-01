<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use TwBundle\Entity\Locatie;
use TwBundle\Entity\Training;
use TwBundle\Entity\VwTraining;
use TwBundle\Form\LocatieType;
use TwBundle\Form\TrainingType;
use TwBundle\Service\LocatieDao;
use TwBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TwBundle\Service\TrainingDao;

/**
 * @Route("/trainingen")
 * @Template
 */
class TrainingenController extends AbstractController
{
    protected $entityName = 'Training';
    protected $entityClass = Training::class;
    protected $formClass = TrainingType::class;
    protected $baseRouteName = 'tw_trainingen_';

    /**
     * @var TrainingDao
     */
    protected $dao;

    /**
     * @param TrainingDao $dao
     */
    public function __construct(TrainingDao $dao)
    {
        $this->dao = $dao;
    }


}
