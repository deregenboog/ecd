<?php

namespace IzBundle\DataFixtures;

use IzBundle\Entity\IzHulpaanbod;
use Faker\Provider\DateTime;

final class IzFakerProvider
{
    /**
     * @return \DateTime|null
     */
    public function koppelingStartdatum(IzHulpaanbod $izHulpaanbod = null)
    {
        if ($izHulpaanbod) {
            return DateTime::dateTimeThisDecade();
        }
    }

    /**
     * @return \DateTime|null
     */
    public function koppelingEinddatum(\DateTime $koppelingStartdatum = null)
    {
        if ($koppelingStartdatum) {
            return DateTime::dateTimeBetween($koppelingStartdatum);
        }
    }
}
