<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\Trajecthouder;
use MwBundle\Form\TrajecthouderType;
use MwBundle\Service\TrajecthouderDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trajecthouders")
 *
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
     * @var TrajecthouderDaoInterface
     */
    protected $dao;

    public function __construct(TrajecthouderDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
