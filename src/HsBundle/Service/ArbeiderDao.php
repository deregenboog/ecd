<?php

namespace HsBundle\Service;

use HsBundle\Entity\Arbeider;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Klant;

class ArbeiderDao extends AbstractDao implements ArbeiderDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'arbeider.actief',
            'klant.id',
            'klant.achternaam',
            'klant.werkgebied',
        ],
    ];

    protected $class = Arbeider::class;

    protected $alias = 'arbeider';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('arbeider.klant', 'klant')
            ->andWhere('klant.disabled = false')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}.
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * @param Klant $klant
     *
     * @return Arbeider
     */
    public function findOneByKlant(Klant $klant)
    {
        return $this->repository->findOneBy(['klant' => $klant]);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Arbeider $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Arbeider $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Arbeider $entity)
    {
        $this->doDelete($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function countByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('arbeider')
            ->select('COUNT(DISTINCT(klant.id)) AS aantal, klant.werkgebied AS stadsdeel')
            ->innerJoin('arbeider.klant', 'klant')
            ->innerJoin('arbeider.registraties', 'registratie')
            ->groupBy('klant.werkgebied')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}.
     */
    public function countNewByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('arbeider')
            ->select('COUNT(DISTINCT(klant.id)) AS aantal, klant.werkgebied AS stadsdeel')
            ->innerJoin('arbeider.klant', 'klant')
            ->groupBy('klant.werkgebied')
        ;

        if ($start) {
            $builder->andWhere('arbeider.inschrijving >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('arbeider.inschrijving <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }
}
