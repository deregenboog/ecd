<?php

namespace OekBundle\Service;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use OekBundle\Entity\Vrijwilliger;
use OekBundle\Entity\Memo;

class VrijwilligerDao extends AbstractDao implements VrijwilligerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'appVrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'appVrijwilliger.id',
            'appVrijwilliger.voornaam',
            'appVrijwilliger.achternaam',
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
            ->select("{$this->alias}, appVrijwilliger")
            ->innerJoin('vrijwilliger.vrijwilliger', 'appVrijwilliger')
            ->leftJoin('appVrijwilliger.werkgebied', 'werkgebied')

        ;

        if ($filter && $filter->vrijwilliger) {
            $filter->vrijwilliger->alias = 'appVrijwilliger';
            $filter->applyTo($builder);
        }
        else if($filter)
        {
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

    /**
     * @param AppVrijwilliger $vrijwilliger
     *
     * @return Vrijwilliger
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

//        $entity->setActief((int)$entity::STATUS_VERWIJDERD);
//        $this->doUpdate($entity);
        $this->doDelete($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function countByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('vrijwilliger')
            ->select('COUNT(DISTINCT(appVrijwilliger.id)) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('vrijwilliger.vrijwilliger', 'appVrijwilliger')
            ->leftJoin('appVrijwilliger.werkgebied', 'werkgebied')
            ->innerJoin('vrijwilliger.registraties', 'registratie')
            ->where("vrijwilliger.actief != :status_verwijderd")
            ->setParameter(":status_verwijderd", Vrijwilliger::STATUS_VERWIJDERD)
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
        $builder = $this->repository->createQueryBuilder('vrijwilliger')
            ->select('COUNT(DISTINCT(appVrijwilliger.id)) AS aantal, werkgebied.naam AS stadsdeel')
            ->innerJoin('vrijwilliger.vrijwilliger', 'appVrijwilliger')
            ->leftJoin('appVrijwilliger.werkgebied', 'werkgebied')
            ->where("vrijwilliger.actief != :status_verwijderd")
            ->setParameter(":status_verwijderd", Vrijwilliger::STATUS_VERWIJDERD)
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
