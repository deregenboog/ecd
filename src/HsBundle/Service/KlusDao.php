<?php

namespace HsBundle\Service;

use HsBundle\Entity\Klus;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

class KlusDao extends AbstractDao implements KlusDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klus.datum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'klus.id',
            'klus.datum',
            'klant.achternaam',
            'klant.werkgebied',
            'activiteit.naam',
        ],
    ];

    protected $class = Klus::class;

    protected $alias = 'klus';

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('klus.klant', 'klant')
            ->innerJoin('klus.activiteit', 'activiteit')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}
     */
    public function create(Klus $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Klus $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Klus $entity)
    {
        $this->doDelete($entity);
    }

    /**
     * {inheritdoc}
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
     * {inheritdoc}
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
