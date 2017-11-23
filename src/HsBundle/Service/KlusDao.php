<?php

namespace HsBundle\Service;

use HsBundle\Entity\Klus;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

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
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin("{$this->alias}.activiteit", 'activiteit')
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
            ->select('COUNT(klus.id) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('klus.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->groupBy('werkgebied.naam')
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
            ->select('COUNT(klus.id) AS aantal, klant.werkgebied AS stadsdeel')
            ->innerJoin('klus.dienstverleners', 'dienstverlener')
            ->innerJoin('dienstverlener.klant', 'klant')
            ->groupBy('klant.werkgebied')
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
    public function countVrijwilligersByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('klus')
            ->select('COUNT(klus.id) AS aantal, basisvrijwilliger.werkgebied AS stadsdeel')
            ->innerJoin('klus.vrijwilligers', 'vrijwilliger')
            ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->groupBy('basisvrijwilliger.werkgebied')
        ;

        if ($start) {
            $builder->andWhere('klus.startdatum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('klus.startdatum <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }
}
