<?php

namespace IzBundle\Service;

use IzBundle\Entity\Hulpvraag;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;

class KoppelingDao extends AbstractDao implements KoppelingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'hulpvraag.koppelingStartdatum',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'hulpvraag.koppelingStartdatum',
            'hulpvraag.koppelingEinddatum',
            'hulpvraag.tussenevaluatiedatum',
            'hulpvraag.eindevaluatiedatum',
            'klant.achternaam',
            'werkgebied.naam',
            'vrijwilliger.achternaam',
            'project.naam',
            'medewerker.achternaam',
        ],
    ];

    protected $class = Hulpvraag::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('hulpvraag')
            ->addSelect('medewerker, project, izKlant, contactOntstaan, klant, hulpaanbod, izVrijwilliger, vrijwilliger, binnengekomenVia')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('izKlant.contactOntstaan', 'contactOntstaan')
            ->innerJoin('hulpvraag.project', 'project')
            ->innerJoin('hulpvraag.medewerker', 'medewerker')
            ->innerJoin('hulpvraag.hulpaanbod', 'hulpaanbod')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('izVrijwilliger.binnengekomenVia', 'binnengekomenVia')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Hulpvraag $koppeling)
    {
        $this->doCreate($koppeling);
    }

    public function update(Hulpvraag $koppeling)
    {
        $this->doUpdate($koppeling);
    }

    public function delete(Hulpvraag $koppeling)
    {
        $this->doDelete($koppeling);
    }
}
