<?php

namespace InloopBundle\Controller;

use AppBundle\Export\ExportInterface;
use AppBundle\Service\VrijwilligerDaoInterface as AppVrijwilligerDaoInterface;
use InloopBundle\Entity\Vrijwilliger;
use InloopBundle\Form\VrijwilligerFilterType;
use InloopBundle\Form\VrijwilligerType;
use InloopBundle\Service\VrijwilligerDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

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
    protected $baseRouteName = 'inloop_vrijwilligers_';

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
