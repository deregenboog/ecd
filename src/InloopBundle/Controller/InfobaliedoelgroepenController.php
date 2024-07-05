<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Infobaliedoelgroep;
use InloopBundle\Form\InfobaliedoelgroepType;
use InloopBundle\Service\InfobaliedoelgroepDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/infobaliedoelgroepen")
 *
 * @Template
 */
class InfobaliedoelgroepenController extends AbstractController
{
    protected $entityName = 'infobaliedoelgroep';
    protected $entityClass = Infobaliedoelgroep::class;
    protected $formClass = InfobaliedoelgroepType::class;
    protected $baseRouteName = 'inloop_infobaliedoelgroepen_';

    /**
     * @var InfobaliedoelgroepDaoInterface
     */
    protected $dao;

    public function __construct(InfobaliedoelgroepDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
