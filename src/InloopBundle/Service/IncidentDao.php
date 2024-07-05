<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Incident;
use InloopBundle\Entity\Locatie;

class IncidentDao extends AbstractDao implements IncidentDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'incident.datum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'geslacht.volledig',
            'locatie.naam',
            'incident.datum',
        ],
    ];

    protected $class = Incident::class;

    protected $alias = 'incident';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.', klant, geslacht, locatie')
            ->innerJoin($this->alias.'.klant', 'klant')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->leftJoin($this->alias.'.locatie', 'locatie')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function findActiefByKlant(Klant $klant)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.klant", 'klant', 'WITH', 'klant = :klant')
            ->where("{$this->alias}.datumTot >= DATE(CURRENT_TIMESTAMP())")
            ->setParameters([
                'klant' => klant,
            ])
        ;

        return $builder->getQuery()->getResult();
    }

    public function findTerugkeergesprekNodigByKlantAndLocatie(Klant $klant, Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.klant", 'klant', 'WITH', 'klant = :klant')
            ->innerJoin("{$this->alias}.locaties", 'locatie', 'WITH', 'locatie = :locatie')
            ->where("{$this->alias}.datumTot <= DATE(CURRENT_TIMESTAMP())")
            ->andWhere("DATEDIFF({$this->alias}.datumTot, {$this->alias}.datumVan) >= 14")
            ->andWhere("{$this->alias}.terugkeergesprekGehad = false")
            ->setParameters([
                'klant' => $klant,
                'locatie' => $locatie,
            ])
        ;

        return $builder->getQuery()->getResult();
    }

    public function findActiefByKlantAndLocatie(Klant $klant, Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.klant", 'klant', 'WITH', 'klant = :klant')
            ->innerJoin("{$this->alias}.locaties", 'locatie', 'WITH', 'locatie = :locatie')
            ->where("{$this->alias}.datumTot >= DATE(CURRENT_TIMESTAMP())")
            ->setParameters([
                'klant' => $klant,
                'locatie' => $locatie,
            ])
        ;

        return $builder->getQuery()->getResult();
    }

    /**
     * @param Incident
     *
     * @return Incident
     */
    public function create(Incident $entity)
    {
        return $this->doCreate($entity);
    }

    /**
     * @param Incident
     *
     * @return Incident
     */
    public function update(Incident $entity)
    {
        return $this->doUpdate($entity);
    }

    /**
     * @param Incident
     *
     * @return Incident
     */
    public function delete(Incident $entity)
    {
        return $this->doDelete($entity);
    }
}
