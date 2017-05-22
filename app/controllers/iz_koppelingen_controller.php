<?php

use AppBundle\Filter\FilterInterface;
use IzBundle\Form\IzKoppelingFilterType;
use IzBundle\Service\KoppelingDaoInterface;

class IzKoppelingenController extends AppController
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
        'koppelingStartdatum',
        'koppelingEinddatum',
        'lopendeKoppelingen',
        'klant' => ['voornaam', 'achternaam', 'stadsdeel'],
        'vrijwilliger' => ['voornaam', 'achternaam'],
        'izProject',
        'izHulpvraagMedewerker',
        'izHulpaanbodMedewerker',
    ];

    /**
     * @var KoppelingDaoInterface
     */
    private $koppelingDao;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->koppelingDao = $this->container->get('iz.dao.koppeling');
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
        $pagination = $this->koppelingDao->findAll($page, $form->getData());

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }

    public function download(FilterInterface $filter)
    {
        $koppelingen = $this->koppelingDao->findAll(null, $filter);

        $filename = sprintf('iz-koppelingen-%s.xls', (new \DateTime())->format('d-m-Y'));
        $this->header('Content-type: application/vnd.ms-excel');
        $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));
        $this->header('Content-Transfer-Encoding: binary');

        $this->set('koppelingen', $koppelingen);
        $this->render('download', false);
    }

    private function createFilter()
    {
        $form = $this->createForm(IzKoppelingFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $form->handleRequest($this->getRequest());

        return $form;
    }
}
