<?php

namespace AppBundle\Util;

class PostcodeFormatter
{
    static public function format($postcode)
    {
        return preg_replace('/\s/', '', strtoupper($postcode));
    }
}
