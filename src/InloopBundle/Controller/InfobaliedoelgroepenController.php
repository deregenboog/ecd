<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Infobaliedoelgroep;
use InloopBundle\Form\InfobaliedoelgroepType;
use InloopBundle\Service\InfobaliedoelgroepDao;
use InloopBundle\Service\InfobaliedoelgroepDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/infobaliedoelgroepen")
 * @Template
 */
class InfobaliedoelgroepenController extends AbstractController
{
    protected $title = 'Infobaliedoelgroepen';
    protected $entityName = 'infobaliedoelgroep';
    protected $entityClass = Infobaliedoelgroep::class;
    protected $formClass = InfobaliedoelgroepType::class;
    protected $baseRouteName = 'inloop_infobaliedoelgroepen_';

    /**
     * @var InfobaliedoelgroepDao
     */
    protected $dao;

    /**
     * @param InfobaliedoelgroepDao $dao
     */
    public function __construct(InfobaliedoelgroepDao $dao)
    {
        $this->dao = $dao;
    }


}
