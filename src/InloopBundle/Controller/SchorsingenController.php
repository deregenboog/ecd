<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use InloopBundle\Form\SchorsingFilterType;
use InloopBundle\Entity\Schorsing;

/**
 * @Route("/schorsingen")
 */
class SchorsingenController extends AbstractController
{
    protected $title = 'Schorsingen';
    protected $entityName = 'schorsing';
    protected $entityClass = Schorsing::class;
    protected $filterFormClass = SchorsingFilterType::class;
    protected $baseRouteName = 'inloop_schorsingen_';

    /**
     * @var SchorsingDaoInterface
     *
     * @DI\Inject("inloop.dao.schorsing")
     */
    protected $dao;
}
