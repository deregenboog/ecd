<?php

namespace IzBundle\DataFixtures;

use Faker\Provider\DateTime;

final class IzFakerProvider
{
    /**
     * @return \DateTime
     */
    public function einddatum(\DateTime $startdatum = null)
    {
        if ($startdatum) {
            return DateTime::dateTimeBetween($startdatum);
        }
    }
}
