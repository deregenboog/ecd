<?php

namespace TwBundle\DataFixtures;

use Faker\Provider\DateTime;

class TwFakerProvider
{
    /**
     * @return \DateTime|null
     */
    public function afsluitdatum(\DateTime $startdatum = null)
    {
        if ($startdatum) {
            return DateTime::dateTimeBetween($startdatum);
        }
    }
}
