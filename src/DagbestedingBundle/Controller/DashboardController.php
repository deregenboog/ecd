<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Medewerker;
use AppBundle\Export\AbstractExport;
use AppBundle\Export\ExportInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\MedewerkerType;
use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Filter\TrajectFilter;
use DagbestedingBundle\Form\TrajectFilterType;
use DagbestedingBundle\Service\TrajectDaoInterface;
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
    protected $title = 'Mijn Dagbesteding';

    /**
     * @var TrajectDaoInterface
     *
     * @DI\Inject("DagbestedingBundle\Service\TrajectDao")
     */
    protected $trajectDao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("dagbesteding.export.trajecten")
     */
    protected $trajectenExport;

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('dagbesteding_dashboard_trajecten');
    }

    /**
     * @Route("/trajecten")
     */
    public function trajectenAction(Request $request)
    {
        $filter = new TrajectFilter();
        $filter->actief = true;
        $filter->medewerker = $this->getMedewerker();

        $form = $this->createForm(TrajectFilterType::class, $filter, [
            'enabled_filters' => [
                'klant' => ['naam'],
                'soort',
                'resultaatgebied' => ['soort'],
                'medewerker',
                'project',
                'locatie',
                'startdatum',
                'rapportage' => ['datum'],
                'filter',
                'download',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('download') && $form->get('download')->isClicked()) {
                return $this->download($filter, $this->trajectDao, $this->trajectenExport, 'trajecten');
            }
        }

        $page = $request->get('page', 1);
        $trajecten = $this->trajectDao->findAll($page, $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $trajecten,
        ];
    }

    /**
     * @Route("/afwezigen")
     */
    public function afwezigenAction(Request $request)
    {
        $filter = new TrajectFilter();
        $filter->afwezig = true;
        $filter->actief = true;
        $filter->medewerker = $this->getMedewerker();

        $form = $this->createForm(TrajectFilterType::class, $filter, [
            'enabled_filters' => [
                'klant' => ['naam'],
                'soort',
                'resultaatgebied' => ['soort'],
                'medewerker',
                'project',
                'locatie',
                'startdatum',
                'rapportage' => ['datum'],
                'filter',
                'download',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('download') && $form->get('download')->isClicked()) {
                return $this->download($filter, $this->trajectDao, $this->trajectenExport, 'trajecten');
            }
        }

        $page = $request->get('page', 1);
        $trajecten = $this->trajectDao->findAll($page, $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $trajecten,
        ];
    }

    /**
     * @Route("/verlengingen")
     */
    public function verlengingenAction(Request $request)
    {
        $filter = new TrajectFilter();
        $filter->verlenging = true;
        $filter->actief = true;
        $filter->medewerker = $this->getMedewerker();

        $form = $this->createForm(TrajectFilterType::class, $filter, [
            'enabled_filters' => [
                'klant' => ['naam'],
                'soort',
                'resultaatgebied' => ['soort'],
                'medewerker',
                'project',
                'locatie',
                'startdatum',
                'rapportage' => ['datum'],
                'filter',
                'download',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('download') && $form->get('download')->isClicked()) {
                return $this->download($filter, $this->trajectDao, $this->trajectenExport, 'trajecten');
            }
        }

        $page = $request->get('page', 1);
        $trajecten = $this->trajectDao->findAll($page, $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $trajecten,
        ];
    }

    /**
     * @Route("/ondersteuningsplan")
     */
    public function ondersteuningsplanAction(Request $request)
    {
        $filter = new TrajectFilter();
        $filter->zonderOndersteuningsplan = true;
        $filter->actief = true;
        $filter->medewerker = $this->getMedewerker();

        $form = $this->createForm(TrajectFilterType::class, $filter, [
            'enabled_filters' => [
                'klant' => ['naam'],
                'soort',
                'resultaatgebied' => ['soort'],
                'medewerker',
                'project',
                'locatie',
                'startdatum',
                'rapportage' => ['datum'],
                'filter',
                'download',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('download') && $form->get('download')->isClicked()) {
                return $this->download($filter, $this->trajectDao, $this->trajectenExport, 'trajecten');
            }
        }

        $page = $request->get('page', 1);
        $trajecten = $this->trajectDao->findAll($page, $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $trajecten,
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

    protected function getMedewerkerForm(Request $request, Medewerker $medewerker)
    {
        return $this->createForm(MedewerkerType::class, $medewerker, [
            'label' => '',
            'method' => 'GET',
        ])->handleRequest($request);
    }

    private function download(FilterInterface $filter, AbstractDao $dao, ExportInterface $export, $name)
    {
        ini_set('memory_limit', '512M');

        $filename = sprintf('mijn-%s-%s.xlsx', $name, (new \DateTime())->format('Y-m-d'));
        $collection = $dao->findAll(null, $filter);

        return $export->create($collection)->getResponse($filename);
    }
}
