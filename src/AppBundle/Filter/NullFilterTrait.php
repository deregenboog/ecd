<?php

namespace AppBundle\Filter;

trait NullFilterTrait
{
    protected function checkForNullValue($val)
    {
        if (is_array($val) && in_array(null, array_values($val))) {
            return true;
        }

        return false;
    }
}
