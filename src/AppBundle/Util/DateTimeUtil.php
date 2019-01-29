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

    /**
     * Return a string representation of the given day of week.
     *
     * @param int $dayOfWeek
     *
     * @return string
     */
    public static function dayOfWeek($dayOfWeek)
    {
        $days = ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'];
        while ($dayOfWeek >= 7) {
            $dayOfWeek = $dayOfWeek % 7;
        }

        return $days[$dayOfWeek];
    }
}
