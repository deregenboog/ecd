<?php

namespace AppBundle\Util;

class DateTimeUtil
{
    /**
     * Combines the date of the first parameter with the time of the second parameter.
     *
     * @param \DateTime $date
     * @param \DateTime $time
     *
     * @return DateTime
     */
    public static function combine(\DateTime $date, \DateTime $time)
    {
        return \DateTime::createFromFormat('Y-m-d H:i:s', implode([
            $date->format('Y-m-d'),
            $time->format('H:i:s'),
        ]));
    }
}
