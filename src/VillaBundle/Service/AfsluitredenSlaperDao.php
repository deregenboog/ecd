<?php

namespace VillaBundle\Service;

use AppBundle\Service\AbstractDao;
use VillaBundle\Entity\AfsluitredenSlaper;

class AfsluitredenSlaperDao extends AbstractDao implements AfsluitredenSlaperDaoInterface
{
    protected $class = AfsluitredenSlaper::class;

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
    public function create(AfsluitredenSlaper $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(AfsluitredenSlaper $entity)
    {
        $this->doUpdate($entity);
    }
}
