<?php

namespace HsBundle\DataFixtures;

use Fidry\AliceDataFixtures\ProcessorInterface;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Registratie;

final class HsProcessor implements ProcessorInterface
{
    public function preProcess(string $fixtureId, $object): void
    {
        if ($object instanceof Klus) {
            $object->getKlant()->addKlus($object);
        }

        if ($object instanceof Registratie) {
            $object->setArbeider($object->getKlus()->getDienstverleners()[0]);
        }
    }

    public function postProcess(string $fixtureId, $object): void
    {
        // do nothing
    }
}
