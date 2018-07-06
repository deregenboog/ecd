<?php

use AppBundle\Form\VrijwilligerFilterType;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\GaVrijwilligerIntake;
use GaBundle\Form\GaVrijwilligerIntakeFilterType;
use GaBundle\Form\GaVrijwilligerSelectType;
use Symfony\Component\Form\FormInterface;

class GroepsactiviteitenVrijwilligersController extends AppController
{
    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    public $uses = [];

    private $enabledFilters = [
        'vrijwilliger' => ['id', 'naam', 'geboortedatumRange', 'medewerker'],
        'intakedatum',
        'afsluitdatum',
        'open',
    ];

    private $sortFieldWhitelist = [
        'vrijwilliger.id',
        'vrijwilliger.achternaam',
        'vrijwilliger.geboortedatum',
        'medewerker.achternaam',
        'intake.intakedatum',
        'intake.afsluitdatum',
    ];

    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(GaVrijwilligerIntake::class);
        $builder = $repository->createQueryBuilder('intake')
            ->select('intake, vrijwilliger, medewerker')
            ->innerJoin('intake.vrijwilliger', 'vrijwilliger')
            ->innerJoin('vrijwilliger.medewerker', 'medewerker')
        ;

        $filter = $this->createFilter();
        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
            if ($filter->get('download')->isClicked()) {
                return $this->download($builder);
            }
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'vrijwilliger.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
            'wrap-queries' => true, // because of HAVING clause in filter
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function download(QueryBuilder $builder)
    {
        ini_set('memory_limit', '512M');

        $intakes = $builder->getQuery()->getResult();

        $this->autoRender = false;
        $filename = sprintf('groepsactiviteiten-vrijwilligers-%s.xls', (new \DateTime())->format('d-m-Y'));

        $export = $this->container->get('ga.export.vrijwilligers');
        $export->create($intakes)->send($filename);
    }

    public function add()
    {
        $filterForm = $this->createForm(VrijwilligerFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(GaVrijwilligerSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if (0 === count($selectionForm->get('vrijwilliger')->getConfig()->getAttribute('choice_list')->getValues())) {
            $this->flashError('Geen resultaat gevonden. Zoek opnieuw of maak eerst een basisdossier aan.');

            return $this->redirect([
                'controller' => 'groepsactiviteiten_vrijwilligers',
                'action' => 'add',
            ]);
        }

        if ($filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isValid()) {
            return $this->redirect([
                'controller' => 'groepsactiviteiten',
                'action' => 'verslagen',
                'Vrijwilliger',
                $selectionForm->getData()->getVrijwilliger()->getId(),
            ]);
        }

        $this->set('filterForm', $filterForm->createView());
    }

    /**
     * @var string
     *
     * @return FormInterface
     */
    private function createFilter()
    {
        $filter = $this->createForm(GaVrijwilligerIntakeFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        return $filter;
    }
}
