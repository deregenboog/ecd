<?php

namespace IzBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Medewerker;
use AppBundle\Export\AbstractExport;
use AppBundle\Export\ExportInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\MedewerkerType;
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
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/mijn")
 * @Template
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
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.hulpvragen")
     */
    protected $hulpvragenExport;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.hulpaanbiedingen")
     */
    protected $hulpaanbiedingenExport;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.koppelingen")
     */
    protected $koppelingenExport;

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

        $medewerkerForm = $this->createForm(MedewerkerType::class, $medewerker, [
            'label' => '',
            'method' => 'GET',
        ])->handleRequest($request);

        $filter = new HulpvraagFilter();
        $filter->medewerker = $medewerker;

        $form = $this->createForm(HulpvraagFilterType::class, $filter, [
            'enabled_filters' => [
                'startdatum',
                'klant' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'project',
                'filter',
                'download',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('download') && $form->get('download')->isClicked()) {
                return $this->download($filter, $this->hulpvraagDao, $this->hulpvragenExport, 'hulpvragen');
            }
        }

        $page = $request->get('page', 1);
        $hulpvragen = $this->hulpvraagDao->findAll($page, $filter);

        return [
            'medewerker_filter' => $medewerkerForm->createView(),
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

        $medewerkerForm = $this->createForm(MedewerkerType::class, $medewerker, [
            'label' => '',
            'method' => 'GET',
        ])->handleRequest($request);

        $filter = new HulpaanbodFilter();
        $filter->medewerker = $medewerker;

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
            'medewerker_filter' => $medewerkerForm->createView(),
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

        $medewerkerForm = $this->createForm(MedewerkerType::class, $medewerker, [
            'label' => '',
            'method' => 'GET',
        ])->handleRequest($request);

        $filter = new KoppelingFilter();
        $filter->medewerker = $medewerker;
        $filter->lopendeKoppelingen = true;

        $form = $this->createForm(KoppelingFilterType::class, $filter, [
            'enabled_filters' => [
                'koppelingStartdatum',
                'klant' => ['voornaam', 'achternaam', 'stadsdeel'],
                'vrijwilliger' => ['voornaam', 'achternaam'],
                'project',
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
            'medewerker_filter' => $medewerkerForm->createView(),
            'filter' => $form->createView(),
            'pagination' => $koppelingen,
        ];
    }

    protected function getMedewerker()
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
