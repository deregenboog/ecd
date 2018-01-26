<?php

namespace AppBundle\Service;

use AppBundle\Entity\Medewerker;

interface MedewerkerDaoInterface
{
    /**
     * @param string $username
     *
     * @return Medewerker
     */
    public function find($username);
}
