<?php

namespace ClipBundle\Controller;

use AppBundle\Export\ExportInterface;
use AppBundle\Service\VrijwilligerDao as AppVrijwilligerDao;
use ClipBundle\Service\VrijwilligerDao;
use ClipBundle\Entity\Vrijwilliger;
use ClipBundle\Form\VrijwilligerCloseType;
use ClipBundle\Form\VrijwilligerFilterType;
use ClipBundle\Form\VrijwilligerType;
use ClipBundle\Service\VrijwilligerDaoInterface;
use InloopBundle\Controller\VrijwilligersControllerAbstract;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vrijwilligers")
 * @Template
 */
class VrijwilligersController extends VrijwilligersControllerAbstract
{
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilliger::class;
    protected $formClass = VrijwilligerType::class;
    protected $filterFormClass = VrijwilligerFilterType::class;
    protected $baseRouteName = 'clip_vrijwilligers_';
    protected $formClassClose = VrijwilligerCloseType::class;

    /**
     * @var VrijwilligerDao
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(VrijwilligerDao $dao, AppVrijwilligerDao $vrijwilligerDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->vrijwilligerDao = $vrijwilligerDao;
        $this->export = $export;
    }
}
