<?php

namespace TwBundle\Controller;


use AppBundle\Export\ExportInterface;

use InloopBundle\Controller\VrijwilligersControllerAbstract;
use TwBundle\Entity\Vrijwilliger;
use TwBundle\Form\VrijwilligerCloseType;
use TwBundle\Form\VrijwilligerFilterType;
use TwBundle\Form\VrijwilligerType;
use TwBundle\Service\VrijwilligerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vrijwilligers")
 * @Template
 */
class VrijwilligersController extends VrijwilligersControllerAbstract
{
    protected $title = 'Vrijwilligers';
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilliger::class;
    protected $formClass = VrijwilligerType::class;
    protected $filterFormClass = VrijwilligerFilterType::class;
    protected $baseRouteName = 'tw_vrijwilligers_';
    protected $formClassClose = VrijwilligerCloseType::class;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("TwBundle\Service\VrijwilligerDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("tw.export.vrijwilliger")
     */
    protected $export;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("AppBundle\Service\VrijwilligerDao")
     */
    protected $vrijwilligerDao;


}
