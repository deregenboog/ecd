<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Koppeling;

class KoppelingDao extends AbstractDao implements KoppelingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'koppeling.startdatum',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'koppeling.startdatum',
            'koppeling.afsluitdatum',
            'klant.achternaam',
            'werkgebied.naam',
            'vrijwilliger.achternaam',
            'medewerkerHulpvraag.voornaam',
            'medewerkerHulpaanbod.voornaam',
            'status.naam',
            'izKlant.datumAanmelding',
            'hulpvraagsoort.naam',
        ],
    ];

    protected $class = Koppeling::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('koppeling')
            ->addSelect('status, hulpvraag, medewerkerHulpvraag, medewerkerHulpaanbod, hulpvraagsoort, izKlant, contactOntstaan, klant, hulpaanbod, izVrijwilliger, vrijwilliger, binnengekomenVia')
            ->innerJoin('koppeling.hulpvraag', 'hulpvraag')
            ->leftJoin('koppeling.status', 'status')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('izKlant.contactOntstaan', 'contactOntstaan')
            ->innerJoin('hulpvraag.hulpvraagsoort', 'hulpvraagsoort')
            ->innerJoin('hulpvraag.medewerker', 'medewerkerHulpvraag')
            ->innerJoin('koppeling.hulpaanbod', 'hulpaanbod')
            ->innerJoin('hulpaanbod.medewerker', 'medewerkerHulpaanbod')
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

    public function create(Koppeling $koppeling)
    {
        $this->doCreate($koppeling);
    }

    public function update(Koppeling $koppeling)
    {
        $this->doUpdate($koppeling);
    }

    public function delete(Koppeling $koppeling)
    {
        $this->doDelete($koppeling);
    }
}
