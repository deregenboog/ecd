<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Schorsing;

class SchorsingDao extends AbstractDao implements SchorsingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'schorsing.datumVan',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'geslacht.volledig',
            'locatie.naam',
            'schorsing.datumVan',
            'schorsing.datumTot',
        ],
    ];

    protected $class = Schorsing::class;

    protected $alias = 'schorsing';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.', klant, geslacht, locatie')
            ->innerJoin($this->alias.'.klant', 'klant')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->leftJoin($this->alias.'.locaties', 'locatie')
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
            ->where("{$this->alias}.datumTot >= :today")
            ->setParameters([
                'klant' => klant,
                'today' => new \DateTime('today'),
            ])
        ;

        return $builder->getQuery()->getResult();
    }

    public function findActiefByKlantAndLocatie(Klant $klant, Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.klant", 'klant', 'WITH', 'klant = :klant')
            ->innerJoin("{$this->alias}.locaties", 'locatie', 'WITH', 'locatie = :locatie')
            ->where("{$this->alias}.datumTot >= :today")
            ->setParameters([
                'klant' => $klant,
                'locatie' => $locatie,
                'today' => new \DateTime('today'),
            ])
        ;

        return $builder->getQuery()->getResult();
    }

    /**
     * @param Schorsing
     *
     * @return Schorsing
     */
    public function create(Schorsing $entity)
    {
        return $this->doCreate($entity);
    }

    /**
     * @param Schorsing
     *
     * @return Schorsing
     */
    public function update(Schorsing $entity)
    {
        return $this->doUpdate($entity);
    }

    /**
     * @param Schorsing
     *
     * @return Schorsing
     */
    public function delete(Schorsing $entity)
    {
        return $this->doDelete($entity);
    }
}
