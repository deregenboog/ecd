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
use IzBundle\Form\KoppelingFilterType;
use IzBundle\Filter\KoppelingFilter;
use IzBundle\Filter\HulpvraagFilter;
use IzBundle\Filter\HulpaanbodFilter;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Form\HulpvraagFilterType;
use IzBundle\Service\HulpvraagDaoInterface;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Form\HulpaanbodFilterType;

/**
 * @Route("/mijn")
 */
class DashboardController extends SymfonyController
{
    protected $title = 'Mijn IZ';

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
     * @var KoppelingDaoInterface
     *
     * @DI\Inject("IzBundle\Service\KoppelingDao")
     */
    protected $koppelingDao;

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('iz_dashboard_koppelingen');
    }

    /**
     * @Route("/hulpvragen")
     */
    public function hulpvragenAction(Request $request)
    {
        $medewerker = $this->getMedewerker();

        $filter = new HulpvraagFilter();
        $form = $this->createForm(HulpvraagFilterType::class, $filter, [
            'enabled_filters' => [
                'startdatum',
                'klant' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'project',
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

        $filter = new HulpaanbodFilter();
        $form = $this->createForm(HulpaanbodFilterType::class, $filter, [
            'enabled_filters' => [
                'startdatum',
                'vrijwilliger' => [
                    'id',
                    'voornaam',
                    'achternaam',
                    'geboortedatumRange',
                    'stadsdeel',
                    'vogAangevraagd',
                    'vogAanwezig',
                    'overeenkomstAanwezig',
                ],
                'project',
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

        $filter = new KoppelingFilter();
        $form = $this->createForm(KoppelingFilterType::class, $filter, [
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
