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
use InloopBundle\Service\VrijwilligerDao;
use InloopBundle\Service\VrijwilligerDaoInterface;
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
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilliger::class;
    protected $formClass = VrijwilligerType::class;
    protected $filterFormClass = VrijwilligerFilterType::class;
    protected $baseRouteName = 'inloop_vrijwilligers_';

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
