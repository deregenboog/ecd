<?php

namespace ErOpUitBundle\Service;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use ErOpUitBundle\Entity\Vrijwilliger;

class VrijwilligerDao extends AbstractDao implements VrijwilligerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'appVrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'appVrijwilliger.achternaam',
            'appVrijwilliger.id',
            'appVrijwilliger.voornaam',
            'vrijwilliger.inschrijfdatum',
            'vrijwilliger.uitschrijfdatum',
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
            ->select("{$this->alias}, appVrijwilliger, werkgebied")
            ->innerJoin($this->alias.'.vrijwilliger', 'appVrijwilliger')
            ->leftJoin('appVrijwilliger.werkgebied', 'werkgebied')
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
    public function findOneByVrijwilliger(AppVrijwilliger $appVrijwilliger)
    {
        return $this->repository->findOneBy(['vrijwilliger' => $appVrijwilliger]);
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
}
