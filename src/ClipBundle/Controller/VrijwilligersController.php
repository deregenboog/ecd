<?php

namespace ClipBundle\Controller;


use AppBundle\Export\ExportInterface;

use InloopBundle\Controller\VrijwilligersControllerAbstract;
use ClipBundle\Entity\Vrijwilliger;
use ClipBundle\Form\VrijwilligerCloseType;
use ClipBundle\Form\VrijwilligerFilterType;
use ClipBundle\Form\VrijwilligerType;
use ClipBundle\Service\VrijwilligerDaoInterface;
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
    protected $baseRouteName = 'clip_vrijwilligers_';
    protected $formClassClose = VrijwilligerCloseType::class;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("ClipBundle\Service\VrijwilligerDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.vrijwilliger")
     */
    protected $export;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("ClipBundle\Service\VrijwilligerDao")
     */
    protected $vrijwilligerDao;


}
