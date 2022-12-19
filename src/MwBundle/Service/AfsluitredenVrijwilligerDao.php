<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\AfsluitredenVrijwilliger;

class AfsluitredenVrijwilligerDao extends AbstractDao implements AfsluitredenVrijwilligerDaoInterface
{
    protected $class = AfsluitredenVrijwilliger::class;

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
    public function create(AfsluitredenVrijwilliger $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(AfsluitredenVrijwilliger $entity)
    {
        $this->doUpdate($entity);
    }
}
