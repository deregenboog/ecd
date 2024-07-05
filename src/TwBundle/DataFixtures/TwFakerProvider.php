<?php

namespace TwBundle\DataFixtures;

use Faker\Provider\Base;
use Faker\Provider\DateTime;

final class TwFakerProvider extends Base
{
    /**
     * @return \DateTime|null
     */
    public function afsluitdatum(?\DateTime $startdatum = null)
    {
        if ($startdatum) {
            return DateTime::dateTimeBetween($startdatum);
        }
    }
}
