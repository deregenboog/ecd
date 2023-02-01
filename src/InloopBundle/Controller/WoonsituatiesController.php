<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Woonsituatie;
use InloopBundle\Form\WoonsituatieType;
use InloopBundle\Service\WoonsituatieDao;
use InloopBundle\Service\WoonsituatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/woonsituaties")
 * @Template
 */
class WoonsituatiesController extends AbstractController
{
    protected $entityName = 'woonsituatie';
    protected $entityClass = Woonsituatie::class;
    protected $formClass = WoonsituatieType::class;
    protected $baseRouteName = 'inloop_woonsituaties_';

    /**
     * @var WoonsituatieDao
     */
    protected $dao;

    /**
     * @param WoonsituatieDao $dao
     */
    public function __construct(WoonsituatieDao $dao)
    {
        $this->dao = $dao;
    }


}
