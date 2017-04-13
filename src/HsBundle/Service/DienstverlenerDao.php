<?php

namespace HsBundle\Service;

use HsBundle\Entity\Dienstverlener;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Klant;

class DienstverlenerDao extends AbstractDao implements DienstverlenerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'wrap-queries' => true, // because of HAVING clause in filter
        'sortFieldWhitelist' => [
            'dienstverlener.actief',
            'klant.id',
            'klant.achternaam',
            'klant.werkgebied',
        ],
    ];

    protected $class = Dienstverlener::class;

    protected $alias = 'dienstverlener';

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('dienstverlener.klant', 'klant')
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
     * {inheritdoc}
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
     * {inheritdoc}
     */
    public function create(Dienstverlener $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Dienstverlener $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Dienstverlener $entity)
    {
        $this->doDelete($entity);
    }
}
