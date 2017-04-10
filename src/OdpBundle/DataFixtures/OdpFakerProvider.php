<?php

namespace OdpBundle\DataFixtures;

use Faker\Provider\DateTime;

class OdpFakerProvider
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
