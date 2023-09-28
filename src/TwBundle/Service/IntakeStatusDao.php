<?php

namespace TwBundle\Service;

use AppBundle\Service\AbstractDao;
use TwBundle\Entity\IntakeStatus;

class IntakeStatusDao extends AbstractDao
{
    protected $class = IntakeStatus::class;

    protected $alias = 'intakestatus';

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
    public function create(IntakeStatus $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(IntakeStatus $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(IntakeStatus $entity)
    {
        $this->doDelete($entity);
    }
}
