<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use HsBundle\Entity\DeclaratieCategorie;

class DeclaratieCategorieDao extends AbstractDao implements DeclaratieCategorieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'declaratiecategorie.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'declaratiecategorie.id',
            'declaratiecategorie.naam',
        ],
    ];

    protected $class = DeclaratieCategorie::class;

    protected $alias = 'declaratiecategorie';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, declaratie")
            ->leftJoin("{$this->alias}.declaraties", 'declaratie')
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
    public function create(DeclaratieCategorie $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(DeclaratieCategorie $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(DeclaratieCategorie $entity)
    {
        $this->doDelete($entity);
    }
}
