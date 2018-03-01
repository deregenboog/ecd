<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\IzHulpaanbod;
use IzBundle\Form\HulpaanbodType;
use IzBundle\Form\IzHulpaanbodFilterType;
use IzBundle\Service\HulpaanbodDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Form\HulpaanbodConnectType;
use IzBundle\Form\HulpaanbodCloseType;
use AppBundle\Controller\AbstractController;

/**
 * @Route("/hulpaanbiedingen")
 */
class HulpaanbiedingenController extends AbstractController
{
    protected $title = 'Hulpaanbiedingen';
    protected $entityName = 'hulpaanbod';
    protected $entityClass = IzHulpaanbod::class;
    protected $filterFormClass = IzHulpaanbodFilterType::class;
    protected $baseRouteName = 'iz_hulpaanbiedingen_';

    /**
     * @var HulpaanbodDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpaanbodDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.hulpaanbiedingen")
     */
    protected $export;
}
