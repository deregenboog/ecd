<?php

namespace HsBundle\Service;

use HsBundle\Entity\Activiteit;
use AppBundle\Service\AbstractDao;

class ActiviteitDao extends AbstractDao implements ActiviteitDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'activiteit.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'activiteit.id',
            'activiteit.naam',
        ],
    ];

    protected $class = Activiteit::class;

    protected $alias = 'activiteit';

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1)
    {
        return parent::findAll($page);
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
    public function create(Activiteit $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Activiteit $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Activiteit $entity)
    {
        $this->doDelete($entity);
    }
}
