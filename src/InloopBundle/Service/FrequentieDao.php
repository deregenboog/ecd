<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Frequentie;

class FrequentieDao extends AbstractDao implements FrequentieDaoInterface
{
    protected $class = Frequentie::class;

    protected $alias = 'frequentie';

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
    public function create(Frequentie $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Frequentie $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Frequentie $entity)
    {
        $this->doDelete($entity);
    }
}
