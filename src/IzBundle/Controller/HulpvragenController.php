<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\IzHulpvraag;
use IzBundle\Form\HulpvraagType;
use IzBundle\Form\IzHulpvraagFilterType;
use IzBundle\Service\HulpvraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Form\HulpvraagConnectType;
use IzBundle\Form\HulpvraagCloseType;
use AppBundle\Controller\AbstractController;

/**
 * @Route("/hulpvragen")
 */
class HulpvragenController extends AbstractController
{
    protected $title = 'Hulpvragen';
    protected $entityName = 'hulpvraag';
    protected $entityClass = IzHulpvraag::class;
    protected $filterFormClass = IzHulpvraagFilterType::class;
    protected $baseRouteName = 'iz_hulpvragen_';

    /**
     * @var HulpvraagDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpvraagDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.hulpvragen")
     */
    protected $export;
}
