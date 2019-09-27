<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Afsluitreden;

class AfsluitredenDao extends AbstractDao implements AfsluitredenDaoInterface
{
    protected $class = Afsluitreden::class;

    protected $alias = 'afsluitreden';

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
    public function create(Afsluitreden $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Afsluitreden $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Afsluitreden $entity)
    {
        $this->doDelete($entity);
    }
}
