<?php

namespace AppBundle\Model;

interface ActivatableInterface
{
    public function isActief();

    public function getActief();

    public function setActief(bool $actief);

    public function isDeletable();
}
