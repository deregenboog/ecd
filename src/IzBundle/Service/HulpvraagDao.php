<?php

namespace IzBundle\Service;

use IzBundle\Entity\Hulpvraag;
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

    protected $class = Hulpvraag::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('hulpvraag')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->leftJoin('izKlant.intake', 'intake')
            ->innerJoin('hulpvraag.project', 'project')
            ->innerJoin('hulpvraag.medewerker', 'medewerker')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->where('hulpvraag.hulpaanbod IS NULL')
            ->andWhere('hulpvraag.einddatum IS NULL')
            ->andWhere('izKlant.afsluiting IS NULL')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Hulpvraag $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Hulpvraag $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Hulpvraag $entity)
    {
        $this->doDelete($entity);
    }
}
