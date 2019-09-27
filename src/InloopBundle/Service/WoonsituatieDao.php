<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Woonsituatie;

class WoonsituatieDao extends AbstractDao implements WoonsituatieDaoInterface
{
    protected $class = Woonsituatie::class;

    protected $alias = 'woonsituatie';

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
    public function create(Woonsituatie $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Woonsituatie $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Woonsituatie $entity)
    {
        $this->doDelete($entity);
    }
}
