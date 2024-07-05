<?php

namespace TwBundle\Controller;

use AppBundle\Export\ExportInterface;
use AppBundle\Service\VrijwilligerDaoInterface as AppVrijwilligerDaoInterface;
use InloopBundle\Controller\VrijwilligersControllerAbstract;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\Vrijwilliger;
use TwBundle\Form\VrijwilligerCloseType;
use TwBundle\Form\VrijwilligerFilterType;
use TwBundle\Form\VrijwilligerType;
use TwBundle\Service\VrijwilligerDaoInterface;

/**
 * @Route("/vrijwilligers")
 *
 * @Template
 */
class VrijwilligersController extends VrijwilligersControllerAbstract
{
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilliger::class;
    protected $formClass = VrijwilligerType::class;
    protected $filterFormClass = VrijwilligerFilterType::class;
    protected $baseRouteName = 'tw_vrijwilligers_';
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
