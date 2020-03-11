<?php

namespace HsBundle\Service;

use HsBundle\Entity\Factuur;
use HsBundle\Entity\FactuurSubjectInterface;

interface FactuurFactoryInterface
{
    /**
     * @return Factuur
     */
    public function create(FactuurSubjectInterface $factuurSubject);
}
