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
            'klant.voornaam',
            'klant.achternaam',
            'klant.afwijkendFactuuradres',
            'klant.saldo',
            'werkgebied.naam',
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
            ->leftJoin('klant.werkgebied', 'werkgebied')
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
            ->select('COUNT(DISTINCT(klant.id)) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('klant.klussen', 'klus')
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
    public function countNewByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('klant')
            ->select('COUNT(DISTINCT(klant.id)) AS aantal, werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->groupBy('werkgebied.naam')
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
