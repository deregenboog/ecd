<?php

namespace TwBundle\Service;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use TwBundle\Entity\Vrijwilliger;

class VrijwilligerDao extends AbstractDao implements VrijwilligerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'appVrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'appVrijwilliger.id',
            'appVrijwilliger.voornaam',
            'appVrijwilliger.achternaam',
            'vrijwilliger.aanmelddatum',
            'vrijwilliger.afsluitdatum',
            'locatie.naam',
            'werkgebied.naam',
        ],
    ];

    protected $class = Vrijwilliger::class;

    protected $alias = 'vrijwilliger';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, appVrijwilliger")
            ->innerJoin('vrijwilliger.vrijwilliger', 'appVrijwilliger')
            ->leftJoin('vrijwilliger.locaties', 'locaties')
//            ->leftJoin('vrijwilliger.locatie','locatie')
            ->leftJoin('appVrijwilliger.werkgebied', 'werkgebied')
        ;

        if ($filter) {
            if ($filter->vrijwilliger) {
                $filter->vrijwilliger->alias = 'appVrijwilliger';
            }
            $filter->applyTo($builder);
        }

        if ($page <= 0) {
            return $builder->getQuery()->getResult();
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }

    /**
     * {inheritdoc}.
     */
    public function find($id)
    {
        return parent::find($id);
    }

    public function findOneByVrijwilliger(AppVrijwilliger $vrijwilliger): ?Vrijwilliger
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
    public function countByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('vrijwilliger')
            ->select('COUNT(DISTINCT(appVrijwilliger.id)) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('vrijwilliger.vrijwilliger', 'appVrijwilliger')
            ->leftJoin('appVrijwilliger.werkgebied', 'werkgebied')
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
    public function countNewByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('vrijwilliger')
            ->select('COUNT(DISTINCT(appVrijwilliger.id)) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('vrijwilliger.vrijwilliger', 'appVrijwilliger')
            ->leftJoin('appVrijwilliger.werkgebied', 'werkgebied')
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
}
