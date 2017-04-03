<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzHulpvraag;
use AppBundle\Filter\FilterInterface;

class KoppelingDao extends AbstractDao implements KoppelingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'izHulpvraag.koppelingStartdatum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'izHulpvraag.koppelingStartdatum',
            'izHulpvraag.koppelingEinddatum',
            'klant.achternaam',
            'klant.werkgebied',
            'vrijwilliger.achternaam',
            'izProject.naam',
            'medewerker.achternaam',
        ],
    ];

    protected $class = IzHulpvraag::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('izHulpvraag')
            ->innerJoin('izHulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
            ->innerJoin('izHulpvraag.izProject', 'izProject')
            ->innerJoin('izHulpvraag.medewerker', 'medewerker')
            ->innerJoin('izHulpvraag.izHulpaanbod', 'izHulpaanbod')
            ->innerJoin('izHulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->andWhere('klant.disabled = false')
            ->andWhere('vrijwilliger.disabled = false')
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
