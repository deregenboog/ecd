<?php

namespace HsBundle\Entity;

interface FactuurSubjectInterface
{
    /**
     * @return \DateTime
     */
    public function getDatum();

    /**
     * @return \HsBundle\Entity\Klant
     */
    public function getKlus();

    /**
     * @return \HsBundle\Entity\Factuur
     */
    public function getFactuur();

    /**
     * @return self
     */
    public function setFactuur(Factuur $factuur);
}
