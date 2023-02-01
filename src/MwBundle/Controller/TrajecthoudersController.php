<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use MwBundle\Entity\Trajecthouder;
use MwBundle\Form\TrajecthouderType;
use MwBundle\Service\TrajecthouderDao;
use MwBundle\Service\TrajecthouderDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/trajecthouders")
 * @Template
 */
class TrajecthoudersController extends AbstractController
{
    protected $entityName = 'trajecthouder';
    protected $entityClass = Trajecthouder::class;
    protected $formClass = TrajecthouderType::class;
    protected $baseRouteName = 'mw_trajecthouders_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var TrajecthouderDao
     */
    protected $dao;

    /**
     * @param TrajecthouderDao $dao
     */
    public function __construct(TrajecthouderDao $dao)
    {
        $this->dao = $dao;
    }


}
