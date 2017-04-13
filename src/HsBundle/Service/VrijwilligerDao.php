<?php

namespace HsBundle\Service;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use HsBundle\Entity\Vrijwilliger;

class VrijwilligerDao extends AbstractDao implements VrijwilligerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'basisvrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'vrijwilliger.id',
            'basisvrijwilliger.achternaam',
            'basisvrijwilliger.werkgebied',
        ],
    ];

    protected $class = Vrijwilliger::class;

    protected $alias = 'vrijwilliger';

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->andWhere('basisvrijwilliger.disabled = false')
        ;

        if ($filter) {
            $filter->vrijwilliger->alias = 'basisvrijwilliger';
            $filter->applyTo($builder);
        }

        if ($page <= 0) {
            return $builder->getQuery()->getResult();
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }

    /**
     * {inheritdoc}
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
     * {inheritdoc}
     */
    public function create(Vrijwilliger $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Vrijwilliger $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Vrijwilliger $entity)
    {
        $this->doDelete($entity);
    }
}
