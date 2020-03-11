<?php

namespace IzBundle\Service;

use IzBundle\Entity\Reservering;

interface ReserveringDaoInterface
{
    public function create(Reservering $entity);

    public function update(Reservering $entity);
}
