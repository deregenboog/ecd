<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Reservering;
use IzBundle\Form\KoppelingFilterType;
use IzBundle\Form\ReserveringType;
use IzBundle\Service\HulpaanbodDao;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Service\HulpvraagDao;
use IzBundle\Service\HulpvraagDaoInterface;
use IzBundle\Service\ReserveringDao;
use IzBundle\Service\ReserveringDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/reserveringen")
 * @Template
 */
class ReserveringenController extends AbstractController
{
    protected $title = 'Reserveringen';
    protected $entityName = 'reservering';
    protected $entityClass = Reservering::class;
    protected $formClass = ReserveringType::class;
    protected $filterFormClass = KoppelingFilterType::class;
    protected $baseRouteName = 'iz_koppelingen_';
    protected $disabledActions = ['index', 'view', 'delete'];

    /**
     * @var ReserveringDao
     */
    protected $dao;

    /**
     * @var HulpvraagDao
     */
    protected $hulpvraagDao;

    /**
     * @var HulpaanbodDao
     */
    protected $hulpaanbodDao;

    /**
     * @param ReserveringDao $dao
     * @param HulpvraagDao $hulpvraagDao
     * @param HulpaanbodDao $hulpaanbodDao
     */
    public function __construct(ReserveringDao $dao, HulpvraagDao $hulpvraagDao, HulpaanbodDao $hulpaanbodDao)
    {
        $this->dao = $dao;
        $this->hulpvraagDao = $hulpvraagDao;
        $this->hulpaanbodDao = $hulpaanbodDao;
    }


    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $hulpvraag = $this->hulpvraagDao->find($request->get('hulpvraag'));
        $hulpaanbod = $this->hulpaanbodDao->find($request->get('hulpaanbod'));

        $reservering = new Reservering($hulpvraag, $hulpaanbod);

        return $this->processForm($request, $reservering);
    }
}
