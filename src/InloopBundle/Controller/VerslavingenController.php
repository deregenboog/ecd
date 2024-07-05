<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Verslaving;
use InloopBundle\Form\VerslavingType;
use InloopBundle\Service\VerslavingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/verslavingen")
 *
 * @Template
 */
class VerslavingenController extends AbstractController
{
    protected $entityName = 'verslaving';
    protected $entityClass = Verslaving::class;
    protected $formClass = VerslavingType::class;
    protected $baseRouteName = 'inloop_verslavingen_';

    /**
     * @var VerslavingDaoInterface
     */
    protected $dao;

    public function __construct(VerslavingDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
