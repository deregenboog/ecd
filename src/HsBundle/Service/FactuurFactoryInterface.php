<?php

namespace HsBundle\Service;

use HsBundle\Entity\Factuur;
use HsBundle\Entity\FactuurSubjectInterface;

interface FactuurFactoryInterface
{
    /**
     * @param FactuurSubjectInterface $factuurSubject
     *
     * @return Factuur
     */
    public function create(FactuurSubjectInterface $factuurSubject);
}
