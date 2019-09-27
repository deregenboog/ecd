<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use MwBundle\Entity\Doorverwijzing;
use MwBundle\Form\DoorverwijzingType;
use MwBundle\Service\DoorverwijzingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/doorverwijzingen")
 * @Template
 */
class DoorverwijzingenController extends AbstractController
{
    protected $title = 'Doorverwijzingen';
    protected $entityName = 'doorverwijzing';
    protected $entityClass = Doorverwijzing::class;
    protected $formClass = DoorverwijzingType::class;
    protected $baseRouteName = 'mw_doorverwijzingen_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var DoorverwijzingDaoInterface
     *
     * @DI\Inject("MwBundle\Service\DoorverwijzingDao")
     */
    protected $dao;
}
