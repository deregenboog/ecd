<?php

namespace HsBundle\Entity;

interface FactuurSubjectInterface
{
    /**
     * @return \DateTime
     */
    public function getDatum();

    /**
     * @return Klant
     */
    public function getKlus();

    /**
     * @return Factuur
     */
    public function getFactuur();

    /**
     * @return self
     */
    public function setFactuur(Factuur $factuur);
}
