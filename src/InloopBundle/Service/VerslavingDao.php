<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Verslaving;

class VerslavingDao extends AbstractDao implements VerslavingDaoInterface
{
    protected $class = Verslaving::class;

    protected $alias = 'verslaving';

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
    public function create(Verslaving $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Verslaving $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Verslaving $entity)
    {
        $this->doDelete($entity);
    }
}
