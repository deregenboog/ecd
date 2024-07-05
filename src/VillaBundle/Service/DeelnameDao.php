<?php

namespace VillaBundle\Service;

use AppBundle\Service\AbstractDao;
use VillaBundle\Entity\Deelname;

class DeelnameDao extends AbstractDao implements DeelnameDaoInterface
{
    protected $class = Deelname::class;

    protected $alias = 'deelname';

    /**
     * {inheritdoc}.
     */
    public function create(Deelname $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Deelname $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Deelname $entity)
    {
        $this->doDelete($entity);
    }
}
