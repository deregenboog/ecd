<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Koppeling;
use IzBundle\Form\KoppelingFilterType;

/**
 * @Route("/koppelingen")
 */
class KoppelingenController extends AbstractController
{
    protected $title = 'Koppelingen';
    protected $entityName = 'koppeling';
    protected $entityClass = Koppeling::class;
//     protected $formClass = KoppelingType::class;
    protected $filterFormClass = KoppelingFilterType::class;
    protected $baseRouteName = 'iz_koppelingen_';
    protected $disabledActions = ['view', 'add', 'edit', 'delete'];

    /**
     * @var KoppelingDaoInterface
     *
     * @DI\Inject("IzBundle\Service\KoppelingDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.koppelingen")
     */
    protected $export;
}
