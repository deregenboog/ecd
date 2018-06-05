<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Form\KoppelingFilterType;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Service\HulpvraagDaoInterface;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Entity\Reservering;
use IzBundle\Form\ReserveringType;

/**
 * @Route("/reserveringen")
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
     * @var ReserveringDaoInterface
     *
     * @DI\Inject("IzBundle\Service\ReserveringDao")
     */
    protected $dao;

    /**
     * @var HulpvraagDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpvraagDao")
     */
    protected $hulpvraagDao;

    /**
     * @var HulpaanbodDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpaanbodDao")
     */
    protected $hulpaanbodDao;

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
