<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/woonsituaties")
 * @Template
 */
class WoonsituatiesController extends AbstractController
{
    protected $title = 'Woonsituaties';
    protected $entityName = 'woonsituatie';
    protected $entityClass = Woonsituatie::class;
    protected $formClass = WoonsituatieType::class;
    protected $baseRouteName = 'inloop_woonsituaties_';

    /**
     * @var WoonsituatieDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\WoonsituatieDao")
     */
    protected $dao;
}
