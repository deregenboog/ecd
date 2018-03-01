<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzHulpvraag;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;

class KoppelingDao extends AbstractDao implements KoppelingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'izHulpvraag.koppelingStartdatum',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'izHulpvraag.koppelingStartdatum',
            'izHulpvraag.koppelingEinddatum',
            'klant.achternaam',
            'werkgebied.naam',
            'vrijwilliger.achternaam',
            'project.naam',
            'medewerker.achternaam',
        ],
    ];

    protected $class = IzHulpvraag::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('izHulpvraag')
            ->addSelect('medewerker, project, izKlant, contactOntstaan, klant, izHulpaanbod, izVrijwilliger, vrijwilliger, binnengekomenVia')
            ->innerJoin('izHulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('izKlant.contactOntstaan', 'contactOntstaan')
            ->innerJoin('izHulpvraag.project', 'project')
            ->innerJoin('izHulpvraag.medewerker', 'medewerker')
            ->innerJoin('izHulpvraag.izHulpaanbod', 'izHulpaanbod')
            ->innerJoin('izHulpaanbod.izVrijwilliger', 'izVrijwilliger')
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

    public function create(IzHulpvraag $koppeling)
    {
        $this->doCreate($koppeling);
    }

    public function update(IzHulpvraag $koppeling)
    {
        $this->doUpdate($koppeling);
    }

    public function delete(IzHulpvraag $koppeling)
    {
        $this->doDelete($koppeling);
    }
}
