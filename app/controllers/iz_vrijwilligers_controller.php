<?php

use IzBundle\Service\VrijwilligerDaoInterface;
use IzBundle\Form\IzVrijwilligerFilterType;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\VrijwilligerFilterType;
use IzBundle\Form\IzVrijwilligerSelectType;
use AppBundle\Entity\Vrijwilliger;

class IzVrijwilligersController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    private $enabledFilters = [
        'vrijwilliger' => ['id', 'naam', 'geboortedatum', 'stadsdeel'],
        'izProject',
        'medewerker',
    ];

    /**
     * @var VrijwilligerDaoInterface
     */
    private $vrijwilligerDao;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->vrijwilligerDao = $this->container->get('iz.dao.vrijwilliger');
    }

    public function index()
    {
        $form = $this->createFilter();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('download')->isClicked()) {
                return $this->download($form->getData());
            }
        }

        $page = $this->getRequest()->get('page', 1);
        $pagination = $this->vrijwilligerDao->findAll($page, $form->getData());

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }

    public function download(FilterInterface $filter)
    {
        $vrijwilligers = $this->vrijwilligerDao->findAll(null, $filter);

        $filename = sprintf('iz-deelnemers-%s.csv', (new \DateTime())->format('d-m-Y'));
        $this->header('Content-type: text/csv');
        $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));

        $filename = sprintf('iz-deelnemers-%s.xls', (new \DateTime())->format('d-m-Y'));
        $this->header('Content-type: application/vnd.ms-excel');
        $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));
        $this->header('Content-Transfer-Encoding: binary');

        $this->set('vrijwilligers', $vrijwilligers);
        $this->render('download', false);
    }

    public function add($vrijwilligerId = null)
    {
        if ($vrijwilligerId) {
            if ($vrijwilligerId === 'new') {
                return $this->redirect([
                    'controller' => 'vrijwilligers',
                    'action' => 'add',
                ]);
            } else {
                return $this->redirect([
                    'controller' => 'iz_deelnemers',
                    'action' => 'toon_aanmelding',
                    'Vrijwilliger',
                    $vrijwilligerId,
                ]);
            }
        }

        $filterForm = $this->createForm(VrijwilligerFilterType::class, null, [
            'enabled_filters' => ['naam'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(IzVrijwilligerSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isSubmitted() && $selectionForm->isValid()) {
            $izVrijwilliger = $selectionForm->getData();
            if ($izVrijwilliger->getVrijwilliger() instanceof Vrijwilliger) {
                return $this->redirect(['action' => 'add', $izVrijwilliger->getVrijwilliger()->getId()]);
            }

            return $this->redirect(['action' => 'add', 'new']);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    private function createFilter()
    {
        $form = $this->createForm(IzVrijwilligerFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $form->handleRequest($this->getRequest());

        return $form;
    }
}
