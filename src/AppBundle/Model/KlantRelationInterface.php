<?php

namespace AppBundle\Model;

use AppBundle\Entity\Klant;

interface KlantRelationInterface
{
    /**
     * @return Klant
     */
    public function getKlant();

    /**
     * @return string name of klant field in entity
     */
    public function getKlantFieldName(): string;
}
