<?php

namespace AppBundle\Model;

use AppBundle\Entity\Klant;

interface IncidentInterface
{
    public function getKlant();

    public function setKlant(Klant $klant);

    public function getDatum(): ?\DateTime;

    public function setDatum($datum);

    public function getOpmerking();

    public function setOpmerking(?string $opmerking = "");

    public function isPolitie(): bool;

    public function setPolitie(bool $politie);

    public function isAmbulance(): bool;

    public function setAmbulance(bool $ambulance);

    public function isCrisisdienst(): bool;

    public function setCrisisdienst(bool $crisisdienst);

    public function getLocatie();

    public function setLocatie($locatie);
}
