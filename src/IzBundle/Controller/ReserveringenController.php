<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Controller\DisableIndexActionTrait;
use IzBundle\Entity\Reservering;
use IzBundle\Form\KoppelingFilterType;
use IzBundle\Form\ReserveringType;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Service\HulpvraagDaoInterface;
use IzBundle\Service\ReserveringDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/reserveringen")
 * @Template
 */
class ReserveringenController extends AbstractController
{
    use DisableIndexActionTrait;

    protected $entityName = 'reservering';
    protected $entityClass = Reservering::class;
    protected $formClass = ReserveringType::class;
    protected $filterFormClass = KoppelingFilterType::class;
    protected $baseRouteName = 'iz_koppelingen_';
    protected $disabledActions = ['index', 'view', 'delete'];

    /**
     * @var ReserveringDaoInterface
     */
    protected $dao;

    /**
     * @var HulpvraagDaoInterface
     */
    protected $hulpvraagDao;

    /**
     * @var HulpaanbodDaoInterface
     */
    protected $hulpaanbodDao;

    public function __construct(ReserveringDaoInterface $dao, HulpvraagDaoInterface $hulpvraagDao, HulpaanbodDaoInterface $hulpaanbodDao)
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
