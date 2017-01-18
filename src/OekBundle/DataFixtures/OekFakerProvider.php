<?php

namespace OekBundle\DataFixtures;

use Faker\Provider\Person;

final class OekFakerProvider
{
    /**
     * @return string|null
     */
    public function koppelingVerwijzingNaar(\DateTime $afsluiting = null)
    {
        if ($afsluiting) {
            return Person::firstNameMale();
        }
    }

}
