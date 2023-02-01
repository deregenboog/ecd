<?php

namespace MwBundle\Controller;


use AppBundle\Export\ExportInterface;

use InloopBundle\Controller\VrijwilligersControllerAbstract;
use MwBundle\Entity\Vrijwilliger;
use MwBundle\Form\VrijwilligerCloseType;
use MwBundle\Form\VrijwilligerFilterType;
use MwBundle\Form\VrijwilligerType;
use MwBundle\Service\VrijwilligerDao;
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
    protected $baseRouteName = 'mw_vrijwilligers_';
    protected $formClassClose = VrijwilligerCloseType::class;

    /**
     * @var VrijwilligerDao
     */
    protected $dao;

    /**
     * @var \AppBundle\Service\VrijwilligerDao
     */
    protected $vrijwilligerDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param VrijwilligerDao $dao
     * @param \AppBundle\Service\VrijwilligerDao $vrijwilligerDao
     * @param ExportInterface $export
     */
    public function __construct(VrijwilligerDao $dao, \AppBundle\Service\VrijwilligerDao $vrijwilligerDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->vrijwilligerDao = $vrijwilligerDao;
        $this->export = $export;
    }


}
