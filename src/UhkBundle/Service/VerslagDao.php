<?php

namespace UhkBundle\Service;

use AppBundle\Service\AbstractDao;
use UhkBundle\Entity\Verslag;

class VerslagDao extends AbstractDao implements VerslagDaoInterface
{
    protected $class = Verslag::class;

    protected $alias = 'verslag';

    /**
     * {inheritdoc}.
     */
    public function create(Verslag $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Verslag $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Verslag $entity)
    {
        $this->doDelete($entity);
    }
}
