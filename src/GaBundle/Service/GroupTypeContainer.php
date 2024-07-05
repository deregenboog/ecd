<?php

namespace GaBundle\Service;

use Doctrine\ORM\EntityRepository;

class GroupTypeContainer
{
    private array $types = [];

    public function getTypeNames(): array
    {
        return array_keys($this->types);
    }

    public function getType(string $title): EntityRepository
    {
        return $this->types[$title];
    }

    public function setType(string $title, EntityRepository $repo)
    {
        $this->types[$title] = $repo;
    }
}
