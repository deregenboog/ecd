<?php

namespace AppBundle\Model;

trait NotDeletableTrait
{
    public function isDeletable()
    {
        return false;
    }
}
