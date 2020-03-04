<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
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
    protected $title = 'Reserveringen';
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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("IzBundle\Service\ReserveringDao");
        $this->hulpvraagDao = $container->get("IzBundle\Service\HulpvraagDao");
        $this->hulpaanbodDao = $container->get("IzBundle\Service\HulpaanbodDao");
    
        return $previous;
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
