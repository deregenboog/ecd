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
     * @return string Name of klant field in entity.
     */
    public function getKlantFieldName(): string;
}
