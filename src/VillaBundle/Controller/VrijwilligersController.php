<?php

namespace VillaBundle\Controller;

use AppBundle\Export\ExportInterface;
use AppBundle\Service\VrijwilligerDaoInterface as AppVrijwilligerDaoInterface;
use InloopBundle\Controller\VrijwilligersControllerAbstract;
use VillaBundle\Entity\Vrijwilliger;
use VillaBundle\Form\VrijwilligerCloseType;
use VillaBundle\Form\VrijwilligerFilterType;
use VillaBundle\Form\VrijwilligerType;
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
     * @var VrijwilligerDaoInterface
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(VrijwilligerDaoInterface $dao, AppVrijwilligerDaoInterface $vrijwilligerDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->vrijwilligerDao = $vrijwilligerDao;
        $this->export = $export;
    }
}
