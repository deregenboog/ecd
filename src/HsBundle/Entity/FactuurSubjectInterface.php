<?php

namespace HsBundle\Entity;

interface FactuurSubjectInterface
{
    /**
     * @return \HsBundle\Entity\Klant
     */
    public function getKlus();

    /**
     * @return \HsBundle\Entity\Factuur
     */
    public function getFactuur();

    /**
     * @param Factuur $factuur
     *
     * @return self
     */
    public function setFactuur(Factuur $factuur);
}
