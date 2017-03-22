<?php

use IzBundle\Service\KlantDaoInterface;
use IzBundle\Form\IzKlantFilterType;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\KlantFilterType;
use IzBundle\Form\IzKlantSelectType;
use AppBundle\Entity\Klant;

class IzKlantenController extends AppController
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
        'klant' => ['id', 'naam', 'geboortedatum', 'stadsdeel'],
        'izProject',
        'medewerker',
    ];

    /**
     * @var KlantDaoInterface
     */
    private $klantDao;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->klantDao = $this->container->get('iz.dao.klant');
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
        $pagination = $this->klantDao->findAll($page, $form->getData());

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }

    public function download(FilterInterface $filter)
    {
        $klanten = $this->klantDao->findAll(null, $filter);

        $filename = sprintf('iz-deelnemers-%s.csv', (new \DateTime())->format('d-m-Y'));
        $this->header('Content-type: text/csv');
        $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));

        $this->set('klanten', $klanten);
        $this->render('download', false);
    }

    public function add($klantId = null)
    {
        if ($klantId) {
            if ($klantId === 'new') {
                return $this->redirect([
                    'controller' => 'klanten',
                    'action' => 'add',
                ]);
            } else {
                return $this->redirect([
                    'controller' => 'iz_deelnemers',
                    'action' => 'toon_aanmelding',
                    'Klant',
                    $klantId,
                ]);
            }
        }

        $filterForm = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['naam'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(IzKlantSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isSubmitted() && $selectionForm->isValid()) {
            $izKlant = $selectionForm->getData();
            if ($izKlant->getKlant() instanceof Klant) {
                return $this->redirect(['action' => 'add', $izKlant->getKlant()->getId()]);
            }

            return $this->redirect(['action' => 'add', 'new']);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    private function createFilter()
    {
        $form = $this->createForm(IzKlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $form->handleRequest($this->getRequest());

        return $form;
    }
}
