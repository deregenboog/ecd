<?php

namespace DagbestedingBundle\DataFixtures;

use Faker\Provider\DateTime;

class DagbestedingFakerProvider
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
