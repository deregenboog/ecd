<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Export\GenericExport;
use InloopBundle\Service\KlantDaoInterface;
use InloopBundle\Form\KlantFilterType;

/**
 * @Route("/klanten")
 */
class KlantenController extends AbstractController
{
    protected $title = 'Klanten';
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
//     protected $formClass = DeelnemerType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'inloop_klanten_';

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("inloop.dao.klant")
     */
    protected $dao;

//     /**
//      * @var GenericExport
//      *
//      * @DI\Inject("dagbesteding.export.deelnemers")
//      */
//     protected $export;
}
