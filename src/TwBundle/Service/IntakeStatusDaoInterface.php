<?php

namespace TwBundle\Service;

use TwBundle\Entity\IntakeStatus;

interface IntakeStatusDaoInterface
{
    public function find($id);

    public function create(IntakeStatus $entity);

    public function update(IntakeStatus $entity);

    public function delete(IntakeStatus $entity);
}
