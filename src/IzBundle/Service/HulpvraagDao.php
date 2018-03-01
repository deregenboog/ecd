<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzHulpvraag;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;

class HulpvraagDao extends AbstractDao implements HulpvraagDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'hulpvraag.startdatum',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'hulpvraag.startdatum',
            'project.naam',
            'intake.intakeDatum',
            'klant.id',
            'klant.voornaam',
            'klant.achternaam',
            'klant.geboortedatum',
            'klant.laatsteZrm',
            'werkgebied.naam',
            'medewerker.achternaam',
        ],
    ];

    protected $class = IzHulpvraag::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('hulpvraag')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->leftJoin('izKlant.izIntake', 'intake')
            ->innerJoin('hulpvraag.project', 'project')
            ->innerJoin('hulpvraag.medewerker', 'medewerker')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->where('hulpvraag.izHulpaanbod IS NULL')
            ->andWhere('hulpvraag.einddatum IS NULL')
            ->andWhere('izKlant.izAfsluiting IS NULL')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(IzHulpvraag $entity)
    {
        $this->doCreate($entity);
    }

    public function update(IzHulpvraag $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(IzHulpvraag $entity)
    {
        $this->doDelete($entity);
    }
}
