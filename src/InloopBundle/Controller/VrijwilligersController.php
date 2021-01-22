<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use InloopBundle\Entity\Vrijwilliger;
use InloopBundle\Form\VrijwilligerCloseType;
use InloopBundle\Form\VrijwilligerFilterType;
use InloopBundle\Form\VrijwilligerType;
use InloopBundle\Service\VrijwilligerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
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
    protected $baseRouteName = 'inloop_vrijwilligers_';

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\VrijwilligerDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("inloop.export.vrijwilliger")
     */
    protected $export;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("AppBundle\Service\VrijwilligerDao")
     */
    protected $vrijwilligerDao;


}
