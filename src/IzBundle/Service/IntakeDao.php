<?php

namespace IzBundle\Service;

use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Intake;

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
