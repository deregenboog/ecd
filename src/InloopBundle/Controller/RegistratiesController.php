<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use InloopBundle\Entity\Registratie;
use InloopBundle\Form\RegistratieFilterType;
use InloopBundle\Service\RegistratieDaoInterface;
use InloopBundle\Form\RegistratieType;
use AppBundle\Export\ExportInterface;

/**
 * @Route("/registraties")
 */
class RegistratiesController extends AbstractController
{
    protected $title = 'Registraties';
    protected $entityName = 'registratie';
    protected $entityClass = Registratie::class;
    protected $formClass = RegistratieType::class;
    protected $filterFormClass = RegistratieFilterType::class;
    protected $baseRouteName = 'inloop_registraties_';

    /**
     * @var RegistratieDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\RegistratieDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("inloop.export.registraties")
     */
    protected $export;
}
