<?php

namespace IzBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Medewerker;
use AppBundle\Export\AbstractExport;
use AppBundle\Export\ExportInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use IzBundle\Filter\HulpaanbodFilter;
use IzBundle\Filter\HulpvraagFilter;
use IzBundle\Filter\KoppelingFilter;
use IzBundle\Form\HulpaanbodFilterType;
use IzBundle\Form\HulpvraagFilterType;
use IzBundle\Form\KoppelingFilterType;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Service\HulpvraagDaoInterface;
use IzBundle\Service\KoppelingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mijn")
 *
 * @Template
 */
class DashboardController extends SymfonyController
{
    protected $title = 'Mijn IZ';

    /**
     * @var HulpvraagDaoInterface
     */
    protected $hulpvraagDao;

    /**
     * @var HulpaanbodDaoInterface
     */
    protected $hulpaanbodDao;

    /**
     * @var KoppelingDaoInterface
     */
    protected $koppelingDao;

    /**
     * @var AbstractExport
     */
    protected $hulpvragenExport;

    /**
     * @var AbstractExport
     */
    protected $hulpaanbiedingenExport;

    /**
     * @var AbstractExport
     */
    protected $koppelingenExport;

    public function __construct(HulpvraagDaoInterface $hulpvraagDao, HulpaanbodDaoInterface $hulpaanbodDao, KoppelingDaoInterface $koppelingDao, AbstractExport $hulpvragenExport, AbstractExport $hulpaanbiedingenExport, AbstractExport $koppelingenExport)
    {
        $this->hulpvraagDao = $hulpvraagDao;
        $this->hulpaanbodDao = $hulpaanbodDao;
        $this->koppelingDao = $koppelingDao;
        $this->hulpvragenExport = $hulpvragenExport;
        $this->hulpaanbiedingenExport = $hulpaanbiedingenExport;
        $this->koppelingenExport = $koppelingenExport;
    }

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
        $filter = new HulpvraagFilter();
        $filter->medewerker = $this->getMedewerker();

        $form = $this->getForm(HulpvraagFilterType::class, $filter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('download') && $form->get('download')->isClicked()) {
                return $this->download($filter, $this->hulpvraagDao, $this->hulpvragenExport, 'hulpvragen');
            }
        }

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
        $filter = new HulpaanbodFilter();
        $filter->medewerker = $this->getMedewerker();

        $form = $this->getForm(HulpaanbodFilterType::class, $filter, [
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
                'hulpvraagsoort',
                'doelgroep',
                'medewerker',
                'zoekterm',
                'filter',
                'download',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('download') && $form->get('download')->isClicked()) {
                return $this->download($filter, $this->hulpaanbodDao, $this->hulpaanbiedingenExport, 'koppelingen');
            }
        }

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
        $filter = new KoppelingFilter();
        $filter->medewerker = $this->getMedewerker();
        $filter->lopendeKoppelingen = true;

        $form = $this->getForm(KoppelingFilterType::class, $filter, [
            'enabled_filters' => [
                'koppelingStartdatum',
                'klant' => ['voornaam', 'achternaam', 'stadsdeel'],
                'vrijwilliger' => ['voornaam', 'achternaam'],
                'project',
                'hulpvraagsoort',
                'doelgroep',
                'medewerker',
                'filter',
                'download',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('download') && $form->get('download')->isClicked()) {
                return $this->download($filter, $this->koppelingDao, $this->koppelingenExport, 'koppelingen');
            }
        }

        $page = $request->get('page', 1);
        $koppelingen = $this->koppelingDao->findAll($page, $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $koppelingen,
        ];
    }

    protected function getMedewerker(): ?Medewerker
    {
        $medewerkerId = (int) $this->getRequest()->get('medewerker');
        if ($medewerkerId) {
            return $this->getEntityManager()->find(Medewerker::class, $medewerkerId);
        }

        return parent::getMedewerker();
    }

    private function download(FilterInterface $filter, AbstractDao $dao, ExportInterface $export, $name)
    {
        ini_set('memory_limit', '512M');

        $filename = sprintf('mijn-%s-%s.xlsx', $name, (new \DateTime())->format('Y-m-d'));
        $collection = $dao->findAll(null, $filter);

        return $export->create($collection)->getResponse($filename);
    }
}
