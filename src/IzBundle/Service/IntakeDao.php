<?php

namespace IzBundle\Service;

use IzBundle\Entity\Intake;
use AppBundle\Service\AbstractDao;

class IntakeDao extends AbstractDao implements IntakeDaoInterface
{
    protected $class = Intake::class;

    public function create(Intake $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Intake $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Intake $entity)
    {
        $this->doDelete($entity);
    }
}
