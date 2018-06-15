<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use HsBundle\Entity\Klus;

class KlusDao extends AbstractDao implements KlusDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klus.startdatum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'klus.status',
            'klus.startdatum',
            'klus.einddatum',
            'klant.achternaam',
            'werkgebied.naam',
            'activiteit.naam',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Klus::class;

    protected $alias = 'klus';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, activiteit, declaratie, memo, registratie")
            ->leftJoin("{$this->alias}.klant", 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin("{$this->alias}.activiteiten", 'activiteit')
            ->leftJoin("{$this->alias}.declaraties", 'declaratie')
            ->leftJoin("{$this->alias}.memos", 'memo')
            ->leftJoin("{$this->alias}.registraties", 'registratie')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Klus $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Klus $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Klus $entity)
    {
        $this->doDelete($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function countByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('klus')
            ->select('COUNT(DISTINCT klus.id) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('klus.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->groupBy('stadsdeel')
        ;

        if ($start) {
            $builder->andWhere('klus.startdatum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('klus.startdatum <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}.
     */
    public function countDienstverlenersByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('klus')
            ->select('COUNT(DISTINCT klus.id) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('klus.dienstverleners', 'dienstverlener')
            ->innerJoin('klus.registraties', 'registratie', 'WITH', 'registratie.arbeider = dienstverlener')
            ->innerJoin('dienstverlener.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
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
    public function countVrijwilligersByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('klus')
            ->select('COUNT(DISTINCT klus.id) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('klus.vrijwilligers', 'vrijwilliger')
            ->innerJoin('klus.registraties', 'registratie', 'WITH', 'registratie.arbeider = vrijwilliger')
            ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->leftJoin('basisvrijwilliger.werkgebied', 'werkgebied')
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
}
