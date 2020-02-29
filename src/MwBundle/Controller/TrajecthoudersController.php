<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\Trajecthouder;
use MwBundle\Form\TrajecthouderType;
use MwBundle\Service\TrajecthouderDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/trajecthouders")
 * @Template
 */
class TrajecthoudersController extends AbstractController
{
    protected $title = 'Trajecthouders';
    protected $entityName = 'trajecthouder';
    protected $entityClass = Trajecthouder::class;
    protected $formClass = TrajecthouderType::class;
    protected $baseRouteName = 'mw_trajecthouders_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var TrajecthouderDaoInterface
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("MwBundle\Service\TrajecthouderDao");
    }
}
