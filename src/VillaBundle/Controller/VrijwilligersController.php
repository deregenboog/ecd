<?php

namespace VillaBundle\Controller;


use AppBundle\Export\ExportInterface;
use AppBundle\Service\VrijwilligerDao as AppVrijwilligerDao;
use InloopBundle\Controller\VrijwilligersControllerAbstract;
use VillaBundle\Entity\Vrijwilliger;
use VillaBundle\Form\VrijwilligerCloseType;
use VillaBundle\Form\VrijwilligerFilterType;
use VillaBundle\Form\VrijwilligerType;
use VillaBundle\Service\VrijwilligerDao;
use VillaBundle\Service\VrijwilligerDaoInterface;
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
    protected $baseRouteName = 'villa_vrijwilligers_';
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
