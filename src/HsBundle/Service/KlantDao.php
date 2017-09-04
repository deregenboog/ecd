<?php

namespace HsBundle\Service;

use HsBundle\Entity\Klant;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\NoResultException;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.actief',
            'klant.id',
            'klant.achternaam',
            'klant.werkgebied',
        ],
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, factuur, betaling, klus")
            ->leftJoin('klant.facturen', 'factuur')
            ->leftJoin('factuur.betalingen', 'betaling')
            ->leftJoin("{$this->alias}.klussen", 'klus')
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
    public function findFacturabel(AppDateRangeModel $dateRange)
    {
        $builder = $this->buildFacturabelQuery($dateRange)
            ->select("{$this->alias}, klus, declaratie, registratie")
        ;

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}.
     */
    public function countFacturabel(AppDateRangeModel $dateRange)
    {
        $builder = $this->buildFacturabelQuery($dateRange)
            ->select("COUNT(DISTINCT {$this->alias}.id)")
        ;

        try {
            return $builder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $exception) {
            return 0;
        }
    }

    private function buildFacturabelQuery(AppDateRangeModel $dateRange)
    {
        return $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.klussen", 'klus')
            ->leftJoin('klus.declaraties', 'declaratie', 'WITH', 'declaratie.datum <= :end AND declaratie.factuur IS NULL')
            ->leftJoin('klus.registraties', 'registratie', 'WITH', 'registratie.datum <= :end AND registratie.factuur IS NULL')
            ->where('declaratie.id IS NOT NULL OR registratie.id IS NOT NULL')
            ->setParameter('end', $dateRange->getEnd())
        ;
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
    public function create(Klant $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Klant $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Klant $entity)
    {
        $this->doDelete($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function countByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('klant')
            ->select('COUNT(DISTINCT(klant.id)) AS aantal, klant.werkgebied AS stadsdeel')
            ->innerJoin('klant.klussen', 'klus')
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
    public function countNewByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('klant')
            ->select('COUNT(DISTINCT(klant.id)) AS aantal, klant.werkgebied AS stadsdeel')
            ->groupBy('klant.werkgebied')
        ;

        if ($start) {
            $builder->andWhere('klant.inschrijving >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('klant.inschrijving <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }
}
