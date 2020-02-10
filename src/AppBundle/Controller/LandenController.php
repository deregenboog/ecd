<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Land;
use AppBundle\Form\LandFilterType;
use AppBundle\Form\LandType;
use AppBundle\Service\LandDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/landen")
 * @Template
 * @Security("has_role('ROLE_ADMIN')")
 */
class LandenController extends AbstractController
{
    protected $title = 'Landen';
    protected $entityName = 'land';
    protected $entityClass = Land::class;
    protected $formClass = LandType::class;
    protected $filterFormClass = LandFilterType::class;
    protected $baseRouteName = 'app_landen_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var LandDaoInterface
     *
     * @DI\Inject("AppBundle\Service\LandDao")
     */
    protected $dao;
}
