<?php

namespace TwBundle\Service;

use AppBundle\Service\AbstractDao;
use TwBundle\Entity\BinnenVia;

class BinnenViaDao extends AbstractDao implements BinnenViaDaoInterface
{
    protected $class = BinnenVia::class;

    protected $alias = 'binnenVia';

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
    public function create(BinnenVia $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(BinnenVia $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(BinnenVia $entity)
    {
        $this->doDelete($entity);
    }
}
