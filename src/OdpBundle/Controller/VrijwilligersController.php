<?php

namespace OdpBundle\Controller;


use AppBundle\Export\ExportInterface;

use InloopBundle\Controller\VrijwilligersControllerAbstract;
use OdpBundle\Entity\Vrijwilliger;
use OdpBundle\Form\VrijwilligerCloseType;
use OdpBundle\Form\VrijwilligerFilterType;
use OdpBundle\Form\VrijwilligerType;
use OdpBundle\Service\VrijwilligerDaoInterface;
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
    protected $baseRouteName = 'odp_vrijwilligers_';
    protected $formClassClose = VrijwilligerCloseType::class;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("OdpBundle\Service\VrijwilligerDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("odp.export.vrijwilliger")
     */
    protected $export;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("AppBundle\Service\VrijwilligerDao")
     */
    protected $vrijwilligerDao;


}
