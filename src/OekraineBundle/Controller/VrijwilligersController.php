<?php

namespace OekraineBundle\Controller;

use AppBundle\Export\ExportInterface;
use AppBundle\Service\VrijwilligerDaoInterface as AppVrijwilligerDaoInterface;
use OekraineBundle\Entity\Vrijwilliger;
use OekraineBundle\Form\VrijwilligerFilterType;
use OekraineBundle\Form\VrijwilligerType;
use OekraineBundle\Service\VrijwilligerDaoInterface;
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
    protected $baseRouteName = 'oekraine_vrijwilligers_';

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
