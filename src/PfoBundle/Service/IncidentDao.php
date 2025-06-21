<?php

namespace PfoBundle\Service;

use PfoBundle\Entity\Client;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use PfoBundle\Entity\Incident;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class IncidentDao extends AbstractDao
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'incident.datum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'geslacht.volledig',
            'incident.datum',
        ],
    ];

    protected $class = Incident::class;

    protected $alias = 'incident';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias . ', klant, geslacht')
            ->innerJoin($this->alias . '.klant', 'klant')
            ->leftJoin('klant.geslacht', 'geslacht');
        
        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function findActiefByKlant(Client $klant)
    {

        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.klant", 'klant', 'WITH', 'klant = :klant')
            ->where("{$this->alias}.datumTot >= DATE(CURRENT_TIMESTAMP())")
            ->setParameters([
                'klant' => $klant,
            ]);

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
