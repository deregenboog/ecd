<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\AfsluitredenKlant;

class AfsluitredenKlantDao extends AbstractDao implements AfsluitredenKlantDaoInterface
{
    protected $class = AfsluitredenKlant::class;

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
    public function create(AfsluitredenKlant $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(AfsluitredenKlant $entity)
    {
        $this->doUpdate($entity);
    }
}
