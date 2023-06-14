<?php

namespace ClipBundle\Controller;


use AppBundle\Export\ExportInterface;

use ClipBundle\Service\VrijwilligerDao;
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
