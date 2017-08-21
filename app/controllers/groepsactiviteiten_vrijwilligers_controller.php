<?php

use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\GaVrijwilligerIntake;
use GaBundle\Form\GaVrijwilligerIntakeFilterType;
use Symfony\Component\Form\FormInterface;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\VrijwilligerFilterType;
use GaBundle\Form\GaVrijwilligerSelectType;

class GroepsactiviteitenVrijwilligersController extends AppController
{
    private $enabledFilters = [
        'vrijwilliger' => ['id', 'naam', 'geboortedatumRange'],
        'medewerker',
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

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(GaVrijwilligerIntake::class);
        $builder = $repository->createQueryBuilder('intake')
            ->select('intake, vrijwilliger, medewerker')
            ->innerJoin('intake.vrijwilliger', 'vrijwilliger')
            ->innerJoin('vrijwilliger.medewerker', 'medewerker')
            ->andWhere('vrijwilliger.disabled = false')
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
        $intakes = $builder->getQuery()->getResult();

//         $filename = sprintf('groepsactiviteiten-vrijwilligers-%s.csv', (new \DateTime())->format('d-m-Y'));
//         $this->header('Content-type: text/csv');
//         $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));

        $filename = sprintf('groepsactiviteiten-vrijwilligers-%s.xls', (new \DateTime())->format('d-m-Y'));
        $this->header('Content-type: application/vnd.ms-excel');
        $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));
        $this->header('Content-Transfer-Encoding: binary');

        $this->set('intakes', $intakes);
        $this->render('download', false);
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
