<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Werkdoel;

class WerkdoelDao extends AbstractDao implements WerkdoelDaoInterface
{
    protected $class = Werkdoel::class;

    protected $alias = 'werkdoel';

    /**
     * {inheritdoc}.
     */
    public function create(Werkdoel $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Werkdoel $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Werkdoel $entity)
    {
        $this->doDelete($entity);
    }
}
