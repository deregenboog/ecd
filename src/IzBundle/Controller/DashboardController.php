<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Service\BehandelaarDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Entity\Doelgroep;
use IzBundle\Form\DoelgroepType;
use AppBundle\Controller\SymfonyController;
use IzBundle\Service\KoppelingDaoInterface;
use IzBundle\Form\IzKoppelingFilterType;
use IzBundle\Filter\IzKoppelingFilter;
use IzBundle\Filter\IzHulpvraagFilter;
use IzBundle\Filter\IzHulpaanbodFilter;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Form\IzHulpvraagFilterType;
use IzBundle\Service\HulpvraagDaoInterface;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Form\IzHulpaanbodFilterType;

/**
 * @Route("/mijn")
 */
class DashboardController extends SymfonyController
{
    protected $title = 'Mijn IZ';

    /**
     * @var HulpvraagDaoInterface
     *
     * @DI\Inject("iz.dao.hulpvraag")
     */
    protected $hulpvraagDao;

    /**
     * @var HulpaanbodDaoInterface
     *
     * @DI\Inject("iz.dao.hulpaanbod")
     */
    protected $hulpaanbodDao;

    /**
     * @var KoppelingDaoInterface
     *
     * @DI\Inject("iz.dao.koppeling")
     */
    protected $koppelingDao;

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('iz_dashboard_hulpvragen');
    }

    /**
     * @Route("/hulpvragen")
     */
    public function hulpvragenAction(Request $request)
    {
        $medewerker = $this->getMedewerker();

        $filter = new IzHulpvraagFilter();
        $form = $this->createForm(IzHulpvraagFilterType::class, $filter, [
            'enabled_filters' => [
                'startdatum',
                'klant' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'izProject',
                'filter',
            ],
        ]);
        $form->handleRequest($request);
        $filter->medewerker = $medewerker;

        $page = $request->get('page', 1);
        $hulpvragen = $this->hulpvraagDao->findAll($page, $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $hulpvragen,
        ];
    }

    /**
     * @Route("/hulpaanbiedingen")
     */
    public function hulpaanbiedingenAction(Request $request)
    {
        $medewerker = $this->getMedewerker();

        $filter = new IzHulpaanbodFilter();
        $form = $this->createForm(IzHulpaanbodFilterType::class, $filter, [
            'enabled_filters' => [
                'startdatum',
                'vrijwilliger' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'izProject',
                'filter',
            ],
        ]);
        $form->handleRequest($request);
        $filter->medewerker = $medewerker;

        $page = $request->get('page', 1);
        $hulpaanbiedingen = $this->hulpaanbodDao->findAll($page, $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $hulpaanbiedingen,
        ];
    }

    /**
     * @Route("/koppelingen")
     */
    public function koppelingenAction(Request $request)
    {
        $medewerker = $this->getMedewerker();

        $filter = new IzKoppelingFilter();
        $form = $this->createForm(IzKoppelingFilterType::class, $filter, [
            'enabled_filters' => [
                'koppelingStartdatum',
                'klant' => ['voornaam', 'achternaam', 'stadsdeel'],
                'vrijwilliger' => ['voornaam', 'achternaam'],
                'project',
                'filter',
            ],
        ]);
        $form->handleRequest($request);
        $filter->medewerker = $medewerker;
        $filter->lopendeKoppelingen = true;

        $page = $request->get('page', 1);
        $koppelingen = $this->koppelingDao->findAll($page, $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $koppelingen,
        ];
    }
}
