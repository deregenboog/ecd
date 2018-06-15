<?php

namespace HsBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use HsBundle\Entity\Dienstverlener;

class DienstverlenerDao extends AbstractDao implements DienstverlenerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'dienstverlener.actief',
            'dienstverlener.rijbewijs',
            'klant.id',
            'klant.voornaam',
            'klant.achternaam',
            'klant.geboortedatum',
            'werkgebied.naam',
        ],
    ];

    protected $class = Dienstverlener::class;

    protected $alias = 'dienstverlener';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, klus, registratie, memo, document")
            ->innerJoin('dienstverlener.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin("{$this->alias}.klussen", 'klus')
            ->leftJoin("{$this->alias}.registraties", 'registratie')
            ->leftJoin("{$this->alias}.memos", 'memo')
            ->leftJoin("{$this->alias}.documenten", 'document')
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
     * @return Dienstverlener
     */
    public function findOneByKlant(Klant $klant)
    {
        return $this->repository->findOneBy(['klant' => $klant]);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Dienstverlener $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Dienstverlener $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Dienstverlener $entity)
    {
        $this->doDelete($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function countByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('dienstverlener')
            ->select('COUNT(DISTINCT(klant.id)) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('dienstverlener.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('dienstverlener.registraties', 'registratie')
            ->groupBy('stadsdeel')
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
        $builder = $this->repository->createQueryBuilder('dienstverlener')
            ->select('COUNT(DISTINCT(klant.id)) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('dienstverlener.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->groupBy('stadsdeel')
        ;

        if ($start) {
            $builder->andWhere('dienstverlener.inschrijving >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('dienstverlener.inschrijving <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }
}
