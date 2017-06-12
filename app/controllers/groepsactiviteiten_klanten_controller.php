<?php

use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\GaKlantIntake;
use GaBundle\Form\GaKlantIntakeFilterType;
use Symfony\Component\Form\FormInterface;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType;
use GaBundle\Form\GaKlantSelectType;
use Symfony\Component\Form\ChoiceList\LazyChoiceList;

class GroepsactiviteitenKlantenController extends AppController
{
    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    private $enabledFilters = [
        'klant' => ['id', 'naam', 'geboortedatumRange'],
        'medewerker',
        'intakedatum',
        'afsluitdatum',
        'open',
    ];

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'klant.geboortedatum',
        'medewerker.achternaam',
        'intake.intakedatum',
        'intake.afsluitdatum',
    ];

    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(GaKlantIntake::class);
        $builder = $repository->createQueryBuilder('intake')
            ->innerJoin("intake.klant", 'klant')
            ->innerJoin('intake.medewerker', 'medewerker')
            ->andWhere("klant.disabled = false")
        ;

        $filter = $this->createFilter();
        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
            if ($filter->get('download')->isClicked()) {
                return $this->download($builder);
            }
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => "klant.achternaam",
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
            'wrap-queries' => true, // because of HAVING clause in filter
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function download(QueryBuilder $builder)
    {
        $intakes = $builder->getQuery()->getResult();

        $this->autoRender = false;
        $filename = sprintf('groepsactiviteiten-deelnemers-%s.xls', (new \DateTime())->format('d-m-Y'));

        $export = $this->container->get('ga.export.klanten');
        $export->create($intakes)->send($filename);
    }

    public function add()
    {
        $filterForm = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(GaKlantSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if (0 === count($selectionForm->get('klant')->getConfig()->getAttribute('choice_list')->getValues())) {
            $this->flashError('Geen resultaat gevonden. Zoek opnieuw of maak eerst een basisdossier aan.');

            return $this->redirect([
                'controller' => 'groepsactiviteiten_klanten',
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
                'action' => 'intakes',
                'Klant',
                $selectionForm->getData()->getKlant()->getId(),
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
        $filter = $this->createForm(GaKlantIntakeFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        return $filter;
    }
}
