<?php

namespace OekraineBundle\Service;

use AppBundle\Service\AbstractDao;
use OekraineBundle\Entity\AfsluitredenBezoeker;

class AfsluitredenBezoekerDao extends AbstractDao implements AfsluitredenBezoekerDaoInterface
{
    protected $class = AfsluitredenBezoeker::class;

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
    public function create(AfsluitredenBezoeker $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(AfsluitredenBezoeker $entity)
    {
        $this->doUpdate($entity);
    }
}
