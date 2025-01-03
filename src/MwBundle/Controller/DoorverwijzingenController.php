<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\Doorverwijzing;
use MwBundle\Form\DoorverwijzingType;
use MwBundle\Service\DoorverwijzingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/doorverwijzingen")
 *
 * @Template
 */
class DoorverwijzingenController extends AbstractController
{
    protected $entityName = 'doorverwijzing';
    protected $entityClass = Doorverwijzing::class;
    protected $formClass = DoorverwijzingType::class;
    protected $baseRouteName = 'mw_doorverwijzingen_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var DoorverwijzingDaoInterface
     */
    protected $dao;

    public function __construct(DoorverwijzingDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
