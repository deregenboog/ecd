<?php

namespace HsBundle\Service;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use HsBundle\Entity\Vrijwilliger;
use HsBundle\Entity\Dienstverlener;

class VrijwilligerDao extends AbstractDao implements VrijwilligerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'basisvrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'vrijwilliger.actief',
            'vrijwilliger.rijbewijs',
            'basisvrijwilliger.id',
            'basisvrijwilliger.voornaam',
            'basisvrijwilliger.achternaam',
            'basisvrijwilliger.geboortedatum',
            'werkgebied.naam',
        ],
    ];

    protected $class = Vrijwilliger::class;

    protected $alias = 'vrijwilliger';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, basisvrijwilliger, klus, memo, document")
            ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->leftJoin('basisvrijwilliger.werkgebied', 'werkgebied')
            ->leftJoin("{$this->alias}.klussen", 'klus')
            ->leftJoin("{$this->alias}.memos", 'memo')
            ->leftJoin("{$this->alias}.documenten", 'document')
        ;

        if ($filter) {
            if ($filter->vrijwilliger) {
                $filter->vrijwilliger->alias = 'basisvrijwilliger';
            }
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
     * @param AppVrijwilliger $vrijwilliger
     *
     * @return Dienstverlener
     */
    public function findOneByVrijwilliger(AppVrijwilliger $vrijwilliger)
    {
        return $this->repository->findOneBy(['vrijwilliger' => $vrijwilliger]);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Vrijwilliger $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Vrijwilliger $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Vrijwilliger $entity)
    {
        $this->doDelete($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function countByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('vrijwilliger')
            ->select('COUNT(DISTINCT(basisvrijwilliger.id)) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->leftJoin('basisvrijwilliger.werkgebied', 'werkgebied')
            ->innerJoin('vrijwilliger.registraties', 'registratie')
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
    public function countByGgwGebied(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('vrijwilliger')
            ->select('COUNT(DISTINCT(basisvrijwilliger.id)) AS aantal, postcodegebied.naam AS ggwgebied')
            ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->leftJoin('basisvrijwilliger.postcodegebied', 'postcodegebied')
            ->innerJoin('vrijwilliger.registraties', 'registratie')
            ->groupBy('postcodegebied')
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
        $builder = $this->repository->createQueryBuilder('vrijwilliger')
            ->select('COUNT(DISTINCT(basisvrijwilliger.id)) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->leftJoin('basisvrijwilliger.werkgebied', 'werkgebied')
            ->groupBy('stadsdeel')
        ;

        if ($start) {
            $builder->andWhere('vrijwilliger.inschrijving >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('vrijwilliger.inschrijving <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}.
     */
    public function countNewByGgwGebied(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('vrijwilliger')
            ->select('COUNT(DISTINCT(basisvrijwilliger.id)) AS aantal, postcodegebied.naam AS ggwgebied')
            ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->leftJoin('basisvrijwilliger.postcodegebied', 'postcodegebied')
            ->groupBy('postcodegebied')
        ;

        if ($start) {
            $builder->andWhere('vrijwilliger.inschrijving >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('vrijwilliger.inschrijving <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }
}
