<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Land;
use AppBundle\Form\LandFilterType;
use AppBundle\Form\LandType;
use AppBundle\Service\LandDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/landen")
 *
 * @Template
 *
 * @IsGranted("ROLE_ADMIN")
 */
class LandenController extends AbstractController
{
    protected $entityName = 'land';
    protected $entityClass = Land::class;
    protected $formClass = LandType::class;
    protected $filterFormClass = LandFilterType::class;
    protected $baseRouteName = 'app_landen_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var LandDaoInterface
     */
    protected $dao;

    public function __construct(LandDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
