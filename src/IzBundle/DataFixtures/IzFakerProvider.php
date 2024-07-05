<?php

namespace IzBundle\DataFixtures;

use Faker\Provider\Base;
use Faker\Provider\DateTime;
use IzBundle\Entity\Hulpaanbod;

final class IzFakerProvider extends Base
{
    /**
     * @return \DateTime|null
     */
    public function koppelingStartdatum(?Hulpaanbod $hulpaanbod = null)
    {
        if ($hulpaanbod) {
            return DateTime::dateTimeThisDecade();
        }
    }

    /**
     * @return \DateTime|null
     */
    public function koppelingEinddatum(?\DateTime $koppelingStartdatum = null)
    {
        if ($koppelingStartdatum) {
            return DateTime::dateTimeBetween($koppelingStartdatum);
        }
    }
}
