<?php

namespace AppBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Export\AbstractExport;
use AppBundle\Service\VrijwilligerDaoInterface;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\VrijwilligerType;
use AppBundle\Form\VrijwilligerFilterType;

/**
 * @Route("/vrijwilligers")
 */
class VrijwilligersController extends AbstractController
{
    protected $title = 'Vrijwilligers';
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilliger::class;
    protected $formClass = VrijwilligerType::class;
    protected $filterFormClass = VrijwilligerFilterType::class;
    protected $baseRouteName = 'app_vrijwilligers_';

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("AppBundle\Service\VrijwilligerDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("app.export.vrijwilligers")
     */
    protected $export;
}
