<?php

namespace AppBundle\Util;

class PostcodeFormatter
{
    public static function format($postcode)
    {
        return preg_replace('/\s/', '', strtoupper($postcode));
    }
}
