<?php

namespace ScipBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use ScipBundle\Entity\Label;

class LabelDao extends AbstractDao implements LabelDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'label.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'label.id',
            'label.naam',
            'label.actief',
        ],
    ];

    protected $class = Label::class;

    protected $alias = 'label';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Label $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Label $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Label $entity)
    {
        $this->doDelete($entity);
    }
}
