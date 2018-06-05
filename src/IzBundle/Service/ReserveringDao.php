<?php

namespace IzBundle\Service;

use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Reservering;

class ReserveringDao extends AbstractDao implements ReserveringDaoInterface
{
    protected $class = Reservering::class;

    public function create(Reservering $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Reservering $entity)
    {
        $this->doUpdate($entity);
    }
}
