<?php

namespace MwBundle\Service;

use MwBundle\Entity\Resultaat;

interface ResultaatDaoInterface
{
    public function find($id);

    public function create(Resultaat $entity);

    public function update(Resultaat $entity);
}
